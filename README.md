# Devstack

## Requirements
- PHP 8+
- [Composer](https://getcomposer.org/)

## Installation
This assumes that PHP and Composer is already installed. To install the command do:
- Open the terminal and run `composer global require webteractive/devstack`. This will install a CLI command `devstack`.
- Set the configuration by running `devstack config`. This command will ask the `repository`, `branch`, and `token`.
- Once the configuration is set, you can now initialize the EN runtimes. Just run `devstack init <runtime>`. The `<runtime>` can be `aac`, `eepower`, and `control` so to load up AllAboutCircuits runtime, all you have to do is do `devstack init aac`.
- Add `.zshrc` or `.bash_profile` `alias dev="bash dev"` to freely use the `dev` cli.

