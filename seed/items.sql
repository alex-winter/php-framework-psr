truncate table items;

insert into items (`id`, `name`, `created_at`) values
(1, 'Item 1', now()),
(2, 'Item 2', now()),
(3, 'Item 3', now()),
(4, 'Item 4', now());