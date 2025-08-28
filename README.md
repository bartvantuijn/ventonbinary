# Venton Binary

A lightweight developer CLI to simplify local workflows built with [PHP][php] and [Composer][composer], designed for fast local development.

## Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/bartvantuijn/ventonbinary.git
cd ventonbinary
composer install
```

### Adding the Binary to Your PATH

To make the binary accessible from anywhere, add the project path to your PATH.

For example, if your project is located at `/Users/username/Projects/ventonbinary`, add the following line to your `~/.zshrc` or `~/.bashrc`:

```bash
export PATH="/Users/username/Projects/ventonbinary:$PATH"
```

### Reload your shell configuration:

```bash
source ~/.zshrc
```

## Usage

### Check version

```bash
venton --version
```

### List available commands

```bash
venton list
```

---

## Commands

### `local:up`

Starts local Docker containers.

What it does:
- Copies required resources to the Docker directory.
- Ensures the venton Docker network exists.
- Creates a database directory if it doesn’t exist.
- Starts containers defined in `docker-compose.yaml`.

```bash
venton local:up
```

### `local:down`

Stop and remove local Docker containers.

What it does:
- Stops all running containers in the `venton_local` project.

```bash
venton local:down
```

### `onlinq:toggle`

Toggle custom registries (`.npmrc` and `.composer/config`).

- down: disables registries (`.npmrc` → `.npmrc.bk`)
- up: re-enables registries (`.npmrc.bk` → `.npmrc`)
- no argument: toggles automatically based on current state

```bash
venton onlinq:toggle down
venton onlinq:toggle up
venton onlinq:toggle
```

---

### License

Venton Binary is licensed under the _MIT License_. It's free to use, modify, and redistribute for any purpose,
including commercial use. See our [full license][license] for more details.

[php]: https://www.php.net/
[composer]: https://getcomposer.org/
[license]: LICENSE.md
