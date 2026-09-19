#!/usr/bin/env python3
"""
One-off generator: crawls the running anayoga Docker site (localhost:8082)
and writes a static equivalent into static_site/ for GitHub Pages.

Requires the docker compose stack from docker-compose.yml to be up.
"""
import re
import shutil
import subprocess
import urllib.request
from pathlib import Path

BASE_URL = "http://localhost:8082"
ROOT = Path(__file__).parent
SITE = ROOT / "original_site"
DOCS = ROOT / "static_site"


def fetch(path):
    with urllib.request.urlopen(f"{BASE_URL}/{path}") as r:
        return r.read()


def mysql_query(sql):
    out = subprocess.check_output(
        [
            "docker", "compose", "exec", "-T", "db",
            "mysql", "-N", "-uroot", "-prootpass", "anayoga_anayoga", "-e", sql,
        ],
        cwd=ROOT,
    )
    return [line.split("\t") for line in out.decode().strip().splitlines() if line]


def rewrite_links(html):
    html = re.sub(r'showcat\.php\?id=(\d+)', r'category-\1.html', html)
    html = re.sub(r'showprod\.php\?id=(\d+)', r'product-\1.html', html)
    html = re.sub(
        r'thumb\.php\?img=([^&"\']+)&(?:amp;)?scale=([0-9.]+)',
        lambda m: f"thumb/{m.group(2)}/{Path(m.group(1)).stem}.jpg",
        html,
    )
    for name in ("index", "products", "environ", "testimonials",
                 "whatishemp", "order", "tandc", "contact"):
        html = html.replace(f'{name}.php', f'{name}.html')
    html = html.replace('action="message.php"', 'action="mailto:info@anayoga.com"')
    html = re.sub(r'(?s)<b>Warning</b>.*?<br />\n?', '', html)
    return html


def write_html(rel_path, raw_bytes):
    html = rewrite_links(raw_bytes.decode("iso-8859-1"))
    out = DOCS / rel_path
    out.parent.mkdir(parents=True, exist_ok=True)
    out.write_text(html, encoding="iso-8859-1")


def main():
    if DOCS.exists():
        shutil.rmtree(DOCS)
    DOCS.mkdir()

    categories = mysql_query("SELECT catID FROM categories")
    products = mysql_query("SELECT prodID FROM products")
    print(f"Found {len(categories)} categories, {len(products)} products")

    # static-ish top-level pages
    for name in ("index", "products", "environ", "testimonials",
                 "whatishemp", "order", "tandc", "contact"):
        write_html(f"{name}.html", fetch(f"{name}.php"))

    # category pages
    for (cat_id,) in categories:
        write_html(f"category-{cat_id}.html", fetch(f"showcat.php?id={cat_id}"))

    # product pages
    for (prod_id,) in products:
        write_html(f"product-{prod_id}.html", fetch(f"showprod.php?id={prod_id}"))

    # thumbnails: collect distinct (img, scale) pairs actually used
    thumb_targets = set()
    for scale in ("0.1", "0.2"):
        for f in DOCS.glob("*.html"):
            for m in re.finditer(rf'thumb/{re.escape(scale)}/([^"\')\s]+)', f.read_text(encoding="iso-8859-1")):
                thumb_targets.add((m.group(1), scale))

    for img_name, scale in sorted(thumb_targets):
        try:
            data = fetch(f"thumb.php?img={img_name}&scale={scale}")
        except Exception as e:
            print(f"  skip thumb {img_name}@{scale}: {e}")
            continue
        out = DOCS / "thumb" / scale / img_name
        out.parent.mkdir(parents=True, exist_ok=True)
        out.write_bytes(data)
    print(f"Generated {len(thumb_targets)} thumbnails")

    # static assets
    shutil.copy(SITE / "main.css", DOCS / "main.css")
    shutil.copytree(SITE / "images", DOCS / "images")
    shutil.copytree(SITE / "product_imgs", DOCS / "product_imgs")

    (DOCS / ".nojekyll").touch()

    print("Done. Static site written to static_site/")


if __name__ == "__main__":
    main()
