.PHONY: up down build console

up:
	docker-compose up -d;

down:
	docker-compose down;

console:
	docker exec -it php_moucao_analyzer bash;

reload: down up
