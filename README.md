# Magento 2 module. Base module for Studio Raz's modules.
 
A base module for ALL Studio Raz modules. 

The module lists the installed Studio Raz composer packages (`studioraz/*`, as recorded by Composer) on the current site
(Stores › Configuration › StudioRaz › Extensions), see screenshot below.
For every package it shows its description, the installed version, the latest released version and its release date,
whether an update is available and a link to its changelog.

### Update Check
Latest versions and changelog links are read from the StudioRaz extensions feed:
`https://studioraz.github.io/ci-extensions-feed/feed.json` (cached for 1 hour, flushed with the config cache).

The check can be disabled under **Extensions › Update Check** (`srbase/update_check/enabled`). The feed URL is fixed
(`SR\Base\Model\Extension\Feed::FEED_URL`). Feed format:
```json
{
  "schema": 1,
  "packages": {
    "studioraz/magento2-base": {
      "title": "Base",
      "latest_version": "1.10.0",
      "released_at": "2026-11-12T00:00:00+00:00",
      "changelog_url": "https://github.com/studioraz/magento2-base/blob/1.10.0/CHANGELOG.md"
    }
  }
}
```
The package name shown in the list is the feed `title`. For packages missing from the feed it falls back to
`extra.title` of the package composer.json, e.g.:
```json
{
    "name": "studioraz/magento2-pointofsale",
    "extra": {
        "title": "Point of Sale"
    }
}
```

Packages missing from the feed (or when the feed is unreachable) are shown with status "Unknown" and no changelog link.


In addition, it adds 2 extra features that should be utilized by dependent modules: 

### Backend Menu
The module adds a menu item to allow dependent modules add their own links. 
When creating new menu item link in `etc/adminhtml/menu.xml` in order to append it to Studio Raz menu item set `parent="SR_Base::srbase"` 
```xml
<config>
    <menu>        
        <add ... parent="SR_Base::srbase" ... />
    </menu>
</config>
```
![menu](docs/screenshots/menu.png)


### System Configuration Tab
The module adds a system configuration tab that other dependent modules can hook to.
When creating a new system configuration section in `etc/adminhtml/system.xml` in order to append it to Studio Raz tab add `<tab>studioraz</tab>` to it:
```xml
<config>
    <system>
        <section>
            ...
            <tab>studioraz</tab>
            ....
        </section>
    </system>
</config> 
```

![extension-list](docs/screenshots/extension-list.png)

## Releases

Releases follow the [shared Release Please guide](https://github.com/studioraz/ci-templates/blob/master/docs/release-please.md).
Use Conventional Commit PR titles (`feat: add support`, `fix: correct behavior`).
Squash merges use the PR title and body; single-commit PRs must have a matching commit title.

Pushes to `master`, the Monday 06:00 UTC schedule and manual runs maintain the release PR and synchronize shared configuration.
Merging a release PR creates a GitHub Release and an unprefixed SemVer tag, and updates the root `CHANGELOG.md`.
Enrollment preserves existing release history (verified baseline `1.9.0`).

The optional `RELEASE_TOKEN` is forwarded to the shared workflow. Without it, `GITHUB_TOKEN` is used and bot-created PRs do not trigger PR-title CI. Supply a PAT or GitHub App token when those checks are required.
