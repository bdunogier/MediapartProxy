# MediapartProxy
MediapartProxy is a web application allowing to fetch content (using personnal credentials) on password protected sites in order to display them in offline reading application such as Wallabag or Pocket.

## Features
- Fetching content using credentials
- Provide RSS feed with links pointing to the proxy
- Supported sites so far:
  - Mediapart
  - Le monde diplomatique

## Install
See the [INSTALL.md](https://github.com/bdunogier/MediapartProxy/blob/master/INSTALL.md).

## Usage

### Mediapart
#### Access an article:
Add mediapart articles URI at to the URL of your proxy, for example: `http://mediapart.myproxy.com/journal/france/221215/une-benevole-est-condamnee-pour-delit-de-solidarite-avec-les-migrants`

#### Use the RSS Feed
Access the RSS Feed using: `http://mediapart.myproxy.com/articles/feed`

### Le monde diplomatique
*This part of the documentation has not been tested as I don't have an account on le monde diplomatique*

Add Le monde diplomatique articles URI at to the URL of your proxy, for example: `http://lmd.myproxy.com/2015/12/BAUDOIN/54366`

#### Use the RSS Feed
Access the RSS Feed using: `http://lmd.myproxy.com/rss`






