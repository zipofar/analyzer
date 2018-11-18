run:
	docker-compose up -d

run-dev:
	docker-compose -f docker-compose_dev.yml up -d

kill-prod:
	docker-compose kill

kill-dev:
	docker-compose -f docker-compose_dev.yml kill

down-dev:
	docker-compose -f docker-compose_dev.yml down

development-setup:
	docker-compose -f docker-compose_dev.yml run php make dev-setup

test-unit:
	docker-compose -f docker-compose_dev.yml exec php make test-unit

test-func:
	docker-compose -f docker-compose_dev.yml exec php make test-func

ansible-development-setup:
	mkdir -p tmp
	echo '' >> tmp/ansible-vault-password
	ansible-playbook ansible/development.yml -i ansible/development -vv





production-setup:
	docker-compose run php make prod-setup

migrate:
	docker-compose -f docker-compose_dev.yml exec php make migrate

seeder:
	docker-compose -f docker-compose_dev.yml exec php make seeder


ansible-production-setup:
	mkdir -p tmp
	echo '' >> tmp/ansible-vault-password
	ansible-playbook ansible/production.yml -i ansible/production -vv

ansible-vaults-encrypt:
	ansible-vault encrypt ansible/production/group_vars/all/vault.yml

ansible-vaults-decrypt:
	ansible-vault decrypt ansible/production/group_vars/all/vault.yml

update-autoload:
	docker-compose run php make update-autoload

build-dev:
	docker-compose -f docker-compose_dev.yml build

build-prod:
	docker-compose build
