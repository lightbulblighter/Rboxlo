# Blueprint
Blueprint for Rboxlo. Summarizes parts of the application, and provides a birds-eye view of what everything does (or is supposed to do.)

# Hosts
Specific hosts

- Eclipse: `$WEBSITE_DOMAIN`, and all other Roblox subdomains; see manifest
- Tootsie: `tootsie.$WEBSITE_DOMAIN`

# Tree
Tree-view of Rboxlo application

- Rboxlo.Server
    - Run using Node.JS
    - Built as an image using Docker and is run in a container
    - Autoloads websites (Eclipse, Tootsie) and runs services (Tootsie)
    - Eclipse
        - Core is ExpressJS, view system is Handlebars
        - Main website
        - All Roblox and Rboxlo-specific APIs
            - Roblox APIs compatibile with applications designed around the Roblox API, and Roblox applications using it
            - Rboxlo APIs refer to stuff like forum endpoints, marketplace endpoints, etc.
        - User frontend
            - There is no "ban user", "delete thread", "delete server" type of buttons integrated into the frontend if you are a moderator. These are all things that go to Bush. There is nothing moderator-related in Eclipse's frontend, such as an admin panel.
        - Basically where everything happens
    - Tootsie
        - Same core as Eclipse
        - Sensitive website for moderation and control of Rboxlo
        - Master panel
            - Password is independent of users, and is manually set before Rboxlo.Server runs
            - Site-wide banners ("We're going down at 3 P.M. CST", "New event!", etc.)
            - Able to lock down entire site, or conversely bring it back
            - Send Rboxlo bulk emails
            - Deploy clients
            - View of Arbiters running and all servers
            - Can manually speak to Okra
        - Separated from Eclipse for utility and stability (if Eclipse goes down, Tootsie does not)
        - Small and sweet
    - Bush
        - Basically same thing as Tootsie but moderation and not master panel
        - Moderation panel
            - Linked with user accounts, but you need the E-Mail rather than the username, and two-factor authentication is required
            - Moderation of users, forums
            - Approves assets
            - Customer support E-Mail access, CANNOT send bulk E-Mails (rate-limits are very strict for this)
                - ONLY way to access customer support E-Mail is through Tootsie
            - Everything here is logged, and logs go to the master panel
    - Okra
        - Not an Express website
        - Is the middleman for Arbiter communication
        - Tootsie can manually talk to Okra
        - Eclipse talks to Okra automatically to create servers
        - Hosts chatbots (i.e. verification and other things)
            - IRC/Discord support
    - Other containers
        - These are not built into Rboxlo.Server but containers that run alongside it
        - Ghost, a blogging platform
        - MySQL, the database where everything is stored
- Rboxlo.Client
    - .NET
    - Rboxlo.Launcher
        - Delivered to the end-user
        - Joins Rboxlo games for users
        - Discord Rich Presence, allowing people to join each-other via Discord
        - Automatically updates itself and the Roblox game clients
        - Fully modular (not much that is hard-coded; mostly everything is constructed through the website)
        - On-demand patches(??)
        - Installation
            - Installs clients on-demand
            - "Part" downloads
            - **Windows:** Installation defaults to `%localappdata%\$NAME` and creates an entry in the registry for one-click uninstallation via Control Panel
    - Rboxlo.Arbiter
        - Cross platform (Linux/Windows)
        - Delievered to server systems
        - Creates Cron jobs automatically if Unix, installs itself as a Windows service if Windows
        - Hosts games that users join
        - Automatic updates as well and is fully modular
        - Fully licensed; you cannot use it without having a valid license
        - License is given out on a per-gameserver basis
        - WINE(?)
- Rboxlo.Patch
    - Assembly patch files given out to aid patching Roblox game clients
    - `.1337` format, x64dbg patch exports
    - Clients that have patches
        - Notable exception is 2016 (able to be compiled, no point in patching)
        - Security refers to RCEs; via DHTML or otherwise
            - These can be achieved through client-side Lua as well, but those types of patches, even if implemented in Rboxlo, are not listed here because it does not necessarily count as a "patch"
        - Anticheat refers to the very mildest forms of anticheat; removing studio tools, basic textcode patch, etc.
        - All patches patch for "usability"; name changing, extranet connections, rocky, etc. This is a given.
        - RobloxApp 2007: Security
        - RobloxApp 2008: Security
        - RobloxApp 2009: Security, anticheat
        - RobloxApp 2010: Anticheat
        - RobloxApp 2011: Anticheat
        - RobloxPlayer 2012: Anticheat, corescripts
        - RobloxPlayer 2013: Corescripts
        - RobloxPlayer 2014: Corescripts
        - RobloxPlayer 2015
        - RobloxPlayer 2017