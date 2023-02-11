# PriceChecker_eMAG_RO
eMAG.ro Price Checker Symfony App


###  Install Instructions
 - cp .env to env.local(only env.* are in .gitignore)
 - update env with database credits
 - composer install
 - php bin/console doctrine:migrations:migrate

***
### Populate DB with data from eMag.ro for testing purpose

#### using controller:
- {BASE_URL}/populate/{LIMIT}/{SEARCH_TERM}
- ex: http://price-checker.com/populate/100/carti

#### using command(enter keyword and limit):
- php bin/console populate
- Enter keyword to search for: {TYPE KEYWORD}
- Max limit to parse: {TYPE LIMIT INT}

```
php bin/console populate

 Enter keyword to search for: carti
 Max limit to parse: 10
 Search "10" products by keyword "carti"
{"total":10}

Total Execution Time: 0.66272306442261

```
***
### Parsing Products Prices
#### using controller:
- {BASE_URL}/parse
- ex: price-checker.com/parse

#### using command:
- php bin/console parse:all

```
{"id":525,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":526,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":527,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":528,"status":"PRICE_SHOULD_BE_UPDATED","reason":"Price should be updated."}
{"id":529,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Price cannot be updated because the value goes under the minimum."}
{"id":530,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":531,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Price cannot be updated because the value goes under the minimum."}
{"id":532,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":533,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":534,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":535,"status":"PRICE_SHOULD_BE_UPDATED","reason":"Price should be updated."}
{"id":536,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":537,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":538,"status":"PRICE_SHOULD_BE_UPDATED","reason":"Price should be updated."}
{"id":539,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":540,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":541,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Price cannot be updated because the value goes under the minimum."}
{"id":542,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":543,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":544,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":545,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Current price is lowest."}
{"id":546,"status":"PRICE_SHOULD_NOT_BE_UPDATED","reason":"Not found related products."}

```
***
### Captcha 
Periodically, eMag will lock the IP address for completing captcha validation.
Just open any eMag page in browser and if needed complete the captcha.
This usually happens after +- 1000 page parsed.
```
{"id":541,"status":"ERROR","reason":"Captcha Verification. Open in browser any page from eMag and complete captcha."}
```

***
### For updating price on eMag Platform
 ##### !!! IT IS NOT FULLY IMPLEMENTED AS A REAL ACCOUNT FOR API CONNECTION NEEDED
#### using controller:
- {BASE_URL}/emag/update
- ex: price-checker.com/emag/update

#### using command:
- php bin/console parse:all

### Instruction to set cron jobs
- sudo crontab -e
- copy/paste code below:
```
# Parse prices from eMag for all products from DB | daily at 08AM
0 8 * * * php /{_PROJECT_PATH_}/bin/console parse:all

# Update prices on eMag for products with status PRICE_SHOULD_BE_UPDATED | daily at 09AM
0 9 * * * php /{_PROJECT_PATH_}/bin/console parse:all
```
- Replace "{_PROJECT_PATH_}" with project real path(ex: /var/www/price-checker/)