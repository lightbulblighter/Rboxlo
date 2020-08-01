<p align="center">
<img src="https://github.com/lighterlightbulb/Rboxlo/raw/master/logos/slideshow.gif" alt="Logo" width="600">
<br>
</p>
<br>
<p align="center">

<a href="https://github.com/lighterlightbulb/Rboxlo/commits/master">
	<img src="https://img.shields.io/github/commit-activity/m/lighterlightbulb/Rboxlo" alt="GitHub commit activity">
</a>

<a href="https://app.fossa.io/projects/git%2Bgithub.com%2Flighterlightbulb%2FRboxlo?ref=badge_shield">
	<img src="https://app.fossa.io/api/projects/git%2Bgithub.com%2Flighterlightbulb%2FRboxlo.svg?type=shield" alt="FOSSA Status">
</a>

<a href="https://github.com/lighterlightbulb/Rboxlo/blob/master/LICENSE">
	<img src="https://img.shields.io/github/license/lighterlightbulb/Rboxlo" alt="GitHub License">
</a>

</p>

# Overview

Rboxlo is a 100% open source, 100% transparent, not-for-profit Roblox private server. Our mission is to let users play Roblox without restrictions imposed by Roblox, while maintaining transparency. You can learn more about us and our goals [here](https://www.rboxlo.xyz/about/mission).

Some distinct features about Rboxlo:
 - Open source
 - Roblox years 2007 through 2017
 - The source code is dedicated to the public domain, with all waiver of copyright, meaning that anybody can do what they please with the Rboxlo framework.
 - All staff actions are [audited](https://www.rboxlo.xyz/audits/), as the lack of transparency is slowly becoming more and more of an issue within the old Roblox community

# Copyright

## License
In our `LICENSE` file, we define Rboxlo as software dedicated and released into the public domain, and we waive any copyright from the project's source files. Our license file is based off of the [Unlicense.](https://unlicense.org/)

## Roblox Copyrighted Content
No Roblox copyrighted works can be found on here (besides parodies of the official Roblox logo, past or present), meaning you cannot find any Roblox game executables here. On our official website, all Roblox executables hosted there are based off of files made available by Roblox Corporation to the general public, and were not obtained illegally. All contribution made here was not made with the motive of monetary gain; it was simply to make a better Roblox. Should Roblox send us a DMCA, or a Cease and Desist, we will remove the offending items in particular. If they say to take down the website, the website will be taken down. If we have to delete this repository, we shall take this repository down.

This project is for educational purposes only.

## Copyrighted Content Onsite
Rboxlo's infrastructure relies heavily on user content creation, and we understand that some times users may not fully understand copyright laws. That is why we ask that any person(s) finding copyrighted material onsite should submit an E-Mail to copyright@rboxlo.xyz containing a URL for the offending material in particular and we will remove it as soon as possible, with moderation taken on user.

# Contributing

If you find any problem(s) in Rboxlo, feel free to submit an issue. This includes stuff like vulnerabilities, or even the most trivial issues (such as typoes.)

If you know how to fix an issue, feel free to make a pull request for the issue.

If you would like to suggest a feature or change, submit it as an issue as well; it will be given the appropriate tag once we have seen it.

We ask that all pull request authors add this paragraph to the end of their contribution's details so as to retain Rboxlo as 100% public domain software.

```
I dedicate any and all copyright interest in this software to the
public domain. I make this dedication for the benefit of the public at
large and to the detriment of my heirs and successors. I intend this
dedication to be an overt act of relinquishment in perpetuity of all
present and future rights to this software under copyright law.
```

# How to set up

You will need the following to build and run Rboxlo:
- Visual Studio 2019
- Docker
- Git
- Some sort of text editor

Clone the repository using git to a folders with this command:

```
git clone https://github.com/lighterlightbulb/Rboxlo
```

Website setup:
1. Copy `docker-compose.sample.yml` to a file named `docker-compose.yml`, and change the following:
   - Set `MYSQL_PASSWORD` to your preferred MySQL password
   - Set `MYSQL_ROOT_PASSWORD` to the same MySQL password as before
   - If you so wish, change `MYSQL_DATABASE` to your custom database name
2. Go to `/website/nginx/domains.conf` and replace `rboxlo.xyz` with your domain. You have to add each domain as a subdomain on your hosting provider.
3. Open each file in `/website/data/environment/` and edit them. Everything is documented, so this should be a breeze. Make sure the MySQL account credentials are the same ones you set in the `docker-compose.yml` file.
4. Go to the `matchmaker` folder and duplicate `config.sample.json` to a file named `config.json`. You will need to change the MySQL credentials to be the same as done before.
5. Close your editor, navigate to the root Rboxlo folder, and run `docker-compose up --build --force-recreate --remove-orphans`.
6. You have Docker set up, but the website doesn't work yet! That's okay. There should be a new folder named `container` and in it is a folder named `site`. Go to the `data` folder inside `site` and copy all the files from `/website/data/` and put it in there. 
7. Hopefully, everything works server-side.

Gameplay setup:
1. Open up `Rboxlo.sln` in Visual Studio, and change some stuff in the files such as:
   - Logos
   - Project name
   - Authorization tokens (Same as you set in the website!!)
   - Etc.
2. Some projects inside the solution require their packages to be installed in order for them to compile. Run NuGet Package Manager to download all the packages.
3. Build Solution
4. To deploy gameserver versions and client versions, please use the website deployer for assistance. The deployment process is fairly easy. There are no game executables here, so you will need to patch the clients themselves.

# Fair Use

Copyright Disclaimer under section 107 of the Copyright Act of 1976, allowance is made for “fair use” for purposes such as criticism, comment, news reporting, teaching, scholarship, education and research.

Fair use is a use permitted by copyright statute that might otherwise be infringing.

### Fair Use Definition

Fair use is a doctrine in United States copyright law that allows limited use of copyrighted material without requiring permission from the rights holders, such as commentary, criticism, news reporting, research, teaching or scholarship. It provides for the legal, non-licensed citation or incorporation of copyrighted material in another author’s work under a four-factor balancing test.
