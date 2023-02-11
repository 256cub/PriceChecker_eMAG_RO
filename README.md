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


## Support

The team is always here to help you. 
Happen to face an issue? Want to report a bug? 
You can submit one here on GitHub using the [Issue Tracker](https://github.com/256cub/PriceChecker_eMAG_RO/issues/new). 


<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create.
Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request.
You can also simply open an issue with the tag "feature". 
Don't forget to give the project a star! 
**Thanks again !!!**

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/YourNewFeature`)
3. Commit your Changes (`git commit -m 'Add some YourNewFeature'`)
4. Push to the Branch (`git push origin feature/YourNewFeature`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>

## Security

If you discover any security-related issues, please email 256cub@gmail.com instead of using the issue tracker.


---
## Buy Me a Coffee

AMF Bot is and always will be open source, even if I don't get donations. 
That being said, I know there are amazing people who may still want to donate just to show their appreciation.

**Thank you very much in advance !!!**

We accept donations through Ko-fi, PayPal, BTC or ETH. 
You can use the buttons below to donate through your method of choice.

|   Donate With   |                      Address                       |
|:---------------:|:--------------------------------------------------:|
|      Ko-fi      |       [Click Here](https://ko-fi.com/256cub)       |
|     PayPal      | [Click Here](https://paypal.me/256cub) |
|   BTC Address   |         3MsUYeUfmpwVS2QrnRbLpCjGaVn2WDD6sj         |
|   ETH Address   |     0x10cd16ba338661d2FB683B2481f8F5000FEd5663     |


## Credits

- [256cub](https://github.com/256cub)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<p align="right">(<a href="#top">back to top</a>)</p>