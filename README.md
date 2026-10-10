# JSON:API Node Preview Tab

[![Pipeline](https://git.drupalcode.org/project/jsonapi_node_preview_tab/badges/1.0.x/pipeline.svg)](https://git.drupalcode.org/project/jsonapi_node_preview_tab/-/pipelines)
[![Test](https://github.com/Decipher/jsonapi_node_preview_tab/actions/workflows/test.yml/badge.svg?branch=1.0.x)](https://github.com/Decipher/jsonapi_node_preview_tab/actions/workflows/test.yml?query=branch%3A1.0.x)

It adds a "JSON:API" tab to nodes, which shows the node's JSON:API document.
It is a quick way to see how a node looks to a decoupled front end.

For a full description of the module, visit the
[project page](https://www.drupal.org/project/jsonapi_node_preview_tab).

Submit bug reports and feature suggestions, or track changes in the
[issue queue](https://git.drupalcode.org/project/jsonapi_node_preview_tab/-/work_items).

## Table of contents

- Requirements
- Installation
- Usage
- Maintainers

## Requirements

- Drupal 10.3 or 11
- JSON:API (`jsonapi`, Drupal core)
- Node (`node`, Drupal core)

## Installation

1. Download and install via Composer:

   ```bash
   composer require drupal/jsonapi_node_preview_tab
   ```

2. Enable the module:

   ```bash
   drush en jsonapi_node_preview_tab
   ```

## Usage

1. Give the "Access JSON:API preview tab" permission to each role that should
   see the tab.
2. View a node. The "JSON:API" tab shows the node's JSON:API document.

The content overview also gets a "JSON" operation for each node.

The tab shows the document in an iframe, so the browser draws it. Firefox may
offer to download the document instead of showing it. A JSON viewer add-on,
such as [JSON Lite](https://addons.mozilla.org/en-US/firefox/addon/json-lite/),
shows it in the page. Add-ons for other browsers can format the document too.

## Maintainers

- Martin Anderson-Clutz - [mandclu](https://www.drupal.org/u/mandclu)
- Stuart Clark - [deciphered](https://www.drupal.org/u/deciphered)
