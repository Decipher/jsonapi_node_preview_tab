# Scaffold differences

The development tooling in this repository comes from
[Drupal Extension Scaffold](https://github.com/AlexSkrypnyk/drupal_extension_scaffold)
4.19.0. None of it is needed to contribute: a Drupal.org merge request is
enough. None of it is in a release either, because `.gitattributes` marks
it `export-ignore`.

This file lists every place where the repository differs from the scaffold's
own output, and why. Read it before you update the scaffold, because an update
overwrites these files and the changes below have to be put back.

## Init answers

To reproduce the starting point, run `php init.php` from the scaffold release
with these answers:

| Prompt | Answer |
| --- | --- |
| Name | `JSON:API Node Preview Tab` |
| Machine name | `jsonapi_node_preview_tab` |
| Type | `module` |
| CI provider | `gha` |
| Drupal versions | `10,11` |
| Command wrapper | `ahoy,makefile` |
| Tools | `phpcs,phpstan,rector,cspell,phpunit,functional_javascript,renovate` |
| Cloudflare | `true` |
| Examples | `false` |

Run `php init.php --help` first. The environment variable prefix has changed
between scaffold releases, and a wrong prefix waits for input that never
comes.

The module has no JavaScript, CSS or Twig files yet, so `eslint`, `stylelint`,
`jest` and `twigcs` are left out. Add them back from the same scaffold release
when the module gets front-end assets.

`functional_javascript` stays in, although there are no JavaScript tests. The
scaffold only adds the Mink packages that the functional tests need with that
tool.

## Files that belong to the module

The scaffold does not own these files. Restore them from git after an update:

- `src/`, `tests/`
- `composer.json`, `jsonapi_node_preview_tab.*.yml`,
  `jsonapi_node_preview_tab.module`
- `README.md`
- `.gitlab-ci.yml` and `phpunit.gitlab-ci.xml`
- `SCAFFOLD.md` and `.skip_npm_build`

Delete what the scaffold adds as examples: `config/`, `css/`, `js/`,
`logo.png`, `src/Form/`, `src/JsonapiNodePreviewTabService.php`, the example
tests, and the example `.install`, `.libraries.yml`, `.links.menu.yml` and
`.services.yml` files.

## Changes to scaffold files

### `.github/workflows/`

- `test.yml` and `draft-release-notes.yml` run on `'*.x'`, not `1.x`. The
  pattern matches every release branch, so nothing needs changing when a new
  branch is cut.
- `deploy.yml` is removed. Drupal.org is the canonical repository, so nothing
  is mirrored to it from GitHub.

### `.devtools/`

`deploy` is removed, with its row in `.devtools/README.md`. Only `deploy.yml`
called it.

### `phpcs.xml`

DrupalCI finds `phpcs.xml` in the repository root and uses it. It does not
install `composer.dev.json`, so:

- The `DrevOps` and `PHPCompatibility` rules and the `testVersion` setting
  are removed. Those standards are not installed on Drupal.org.
- `.devtools/` is excluded. DrupalCI passes the whole module folder on the
  command line, which overrides the `<file>` paths.

### `phpstan.neon`

- `*rector.php` is excluded. DrupalCI analyses the module without the Rector
  classes that file imports. There is no leading `*/`, because DrupalCI runs
  from the module root, where the file has no directory prefix.
- A new `ignoreErrors` entry covers `drupalCreateUser()`, which returns
  `User|false` on Drupal 10 and `UserInterface` on Drupal 11. The tests narrow
  the type for Drupal 10, which Drupal 11 reports as redundant.
- Another entry covers `Drupal\Core\Hook\Attribute\Hook`, which Drupal 10 does
  not have. The `#[LegacyHook]` functions in the `.module` file run there instead.

### `.cspell.json`

Adds `Clutz` and `mandclu`, from the maintainer list in `README.md`. The same
words are in `_CSPELL_WORDS` in `.gitlab-ci.yml`, for Drupal.org's spelling job.

### `package.json` and `package-lock.json`

- The description is the module's, not the generated placeholder.
- The `is-positive` dependency is removed. The scaffold adds it as a sample,
  and this module does not use it.
- The `build` script is removed, and `.skip_npm_build` turns off the npm build
  step. The module has no front-end assets to build. `npm run lint-spell`
  still works.

Regenerate the lock file with `npm install --package-lock-only` after you edit
`package.json`.

### `AGENTS.md`

The CI section names GitHub Actions and DrupalCI, and drops the CircleCI and
deployment lines.

### `.gitattributes`

Adds `SCAFFOLD.md`, `.gitlab-ci.yml` and `phpunit.gitlab-ci.xml` to the
`export-ignore` list. Drupal.org packages don't need the CI config.

## Left as the scaffold makes them

The scaffold leaves some blank lines in `.ahoy.yml`, `Makefile` and
`test.yml` where it strips sections for the tools that are left out. They are
left alone, so a fresh `init.php` run compares cleanly with these files.
