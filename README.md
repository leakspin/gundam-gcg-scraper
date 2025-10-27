# Gundam GCG Scraper

This is a small scraper that gets all the cards in the Gundam GCG website and converts them into a useful JSON for anyone to use.

## How it works
```bash
docker run --rm -it -v $(pwd):/src -u $UID$ php bash
cd /src
php scraper.php
```
## Development
In case you don't want to repeatedly get the cards online, you can get a HTML file named `card.html` with the data and change the `LOCAL` const to `true`.