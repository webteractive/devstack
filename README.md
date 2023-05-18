# Devstack

## Requirements
- PHP 8+
- [Composer](https://getcomposer.org/)

## Installation
This assumes that PHP and Composer is already installed. This also assumes that the Composer's bin is already added to your path (Add `export PATH=~/.composer/vendor/bin:$PATH` to your `.zshrc` ). To install the command do:
- Open the terminal and run `composer global require webteractive/devstack`. This will install a CLI command `devstack`.
- Running the `devstack` command should list all available commands that you can use.


## Initializing Runtimes
There are to ways to initialize runtimes.
1. By running `devstack https://github.com/vendor/the-name-of-the-runtime`.
2. By using a private repository. This good for runtimes that you don't want to share to the world.

### Using Private Runtimes
You can do this by following the steps below:
1. Run the `devstack config` command, this will ask the repository url, the branch, and token. The token here is your personal access token. Visit [this](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token) page for more details on how to obtaine one.
2. Once the configuration is done, you may now initialize runtimes from your private repository by doing `devstack init the_runtime_name`.
3. To download the latest runtimes in your repository, run `devstack download`.

