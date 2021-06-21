# Setting up Rboxlo
The process to download and set up a Rboxlo instance is trivial. This is a small guide that details how to set up Rboxlo.

If you experience any issues while setting up Rboxlo, please [file an issue](https://github.com/lightbulblighter/Rboxlo/issues/new) and attach the error message you receieved (or a full error log.) If the instructions are too confusing and need clarification, you are also welcome to file an issue containing what part you got stuck on. It is better to ask for help than move on and potentially leave your Rboxlo instance insecure, opening up holes for attackers to exploit.

## Prerequisites
This guide assumes you have the following applications installed. If you do not have them installed, you should go get them now.

- Git
- A text editor (such as Notepad++, Vim, Notepad, Visual Studio Code)
- A terminal (such as the batch Command Prompt for Windows, or the bash Konsole for Unix systems)
- Docker (on Windows, you can get Docker for Desktop, but on Unix systems you can simply run this one-liner: `sudo apt-get update && sudo apt-get install docker`)

## Guide
1. Clone this repository using Git by running this command in your terminal: `git clone https://github.com/lightbulblighter/Rboxlo`
2. Navigate to the directory you have cloned the repository in.
3. Now we are going to edit our environment. Copy the `.env.sample` file and rename the copied file to `.env`.
4. Open your new `.env` file in your text editor.
5. Read the file thoroughly before making any changes. Then, change the values as you please. Do note that some values are required to change, such as API secrets or passwords.
6. Save and close your `.env` file.
7. We are done editing our environment. We are now going to build the Rboxlo Server.
8. The Rboxlo Server is now set up. Run `docker compose up --build` to build and run it. Assuming you haven't changed `SERVER_DOMAIN`, navigate to `rboxlo.loc` to see the Rboxlo Server in action.

We have finished building the Rboxlo Server.

## Tips & Troubleshooting
- Rboxlo is set up with `rboxlo.loc` everywhere as its default domain. If you are not able to access `rboxlo.loc` locally, copy and paste [this](https://raw.githubusercontent.com/lightbulblighter/Rboxlo/trunk/Setup/hosts) into [your hosts file.](https://www.whatsmydns.net/hosts-file.html) **You do not need to do this if you are in a production environment (i.e. on a datacenter/server.)**
- If the captcha is not working, make sure that you have input the public and site key for **Google's Checkbox reCaptcha v2.**
- Some items that aren't really self-explanatory are `ENCRYPTION_KEY` and `CIPHERSWEET_KEY`. `ENCRYPTION_KEY` is the key used to encrypt user data and is really just a passphrase used as a key, so you can just enter any string and it'll work. The situation for ciphersweet is different though; you'll need a 32-byte hex encoded key for that. In simpler terms, create a passphrase (or 'password' if that term is more familiar for you) that is 32 characters long and enter it into a hex encoder like [this](https://www.convertstring.com/EncodeDecode/HexEncode). That's your new ciphersweet key.
- `ENCRYPTION_KEY` can be anything that is 32 characters long. `SERVER_SESSION_SECRET` and `SERVER_COOKIE_SECRET` can be anything.
- Make sure to make `MAIL_PASSWORD` and `DB_PASSWORD` very long and secure passwords. These passwords will most likely not be used by you personally and only by the server, so memorability is not necessary.
- If you are in a debugging environment, please set `PRODUCTION` in the environment file to `false`. **If you are in a production environment, make sure to set `PRODUCTION` in the environment file to `true`.**
- If you have any other issue not listed here, please do not hesitate to file an issue on the GitHub [here](https://github.com/lightbulblighter/Rboxlo/issues/new).
- For secret keys, you should probably just use a random text generator as the key, as you are not able to access the data. Secret keys are not passwords, and in most cases you will never be using them.
- Be generous! Give your users a nice stipend. The stipend should be balanced with how you are pricing official items on the Rboxlo Catalog. By default, users receieve 25 Rbux daily.
- The timezone (`TZ`) in the environment file is in accordance with the [TZ database](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones). Use the specific city you are hosting Rboxlo in as your timezone.