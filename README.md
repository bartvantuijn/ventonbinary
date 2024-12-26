# Venton Binary

Venton Binary is a simple CLI tool for managing local tasks, such as Docker containers and configuration files.

## Installation

Clone the repository and install dependencies:

```
git clone https://github.com/bartvantuijn/ventonbinary.git
cd ventonbinary
composer install
```

### Adding the Binary to Your PATH

To make the binary accessible from anywhere, add the project path to your PATH.

For example, if your project is located at `/Users/username/Projects/ventonbinary`, add the following line to your `~/.zshrc` or `~/.bashrc`:

```
export PATH="/Users/username/Projects/ventonbinary:$PATH"
```

### Reload your shell configuration:

```
source ~/.zshrc
```

### Verify the CLI is working

```
venton --version
```

## Commands

### LocalUpCommand

Starts local Docker containers based on the provided configuration.

What it does:
- Copies required resources to the Docker directory.
- Ensures the venton Docker network exists (creates it if necessary).
- Creates a database directory if it doesn’t exist.
- Starts the Docker containers defined in docker-compose.yaml.

Examples:

```
venton local:up
```

### LocalDownCommand

Stops and removes the running Docker containers.

What it does:
- Stops all running containers in the venton_local project.

Examples:

```
venton local:down
```

### ToggleOnlinqCommand

Toggles the .npmrc and .composer/config files by renaming them to temporarily disable or re-enable custom registries.

Arguments:
- state (optional): Can be down to disable custom registries or up to re-enable them.

What it does:
- Renames .npmrc to .npmrc.bk and .composer/config to .composer/config.bk when disabling registries (state=down).
- Renames .npmrc.bk back to .npmrc and .composer/config.bk back to .composer/config when re-enabling (state=up).

Examples:

- Disable custom registries
```
venton onlinq:toggle down
```

- Re-enable custom registries 
```
venton onlinq:toggle up
```

- Toggle automatically based on current file state
```
venton onlinq:toggle
```
