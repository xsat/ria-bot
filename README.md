# Ria BOT

Publish new offers from https://dom.ria.com/ website to your telegram channel

**Example of offer:**
![Offer Example](offer_example.png)

## How To Setup

1. Copy file `.env.example` to `.env`
2. Configure new `.env` file
    * Create random hash and replace `APP_SECRET=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX` to `APP_SECRET=MY_NEW_SECRET_HASH` 
    * [Make or use existing telegram BOT token](https://core.telegram.org/bots/api#authorizing-your-bot) and replace `TELEGRAM_TOKEN=XXXXXXXXXX:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX` to `TELEGRAM_TOKEN=MY:TELEGRAM_BOT_TOKEN`
    * 