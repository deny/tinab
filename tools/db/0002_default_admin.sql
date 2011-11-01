-- @1 domyślny administrator
INSERT INTO `users` (`user_id`, `status`, `email`, `passwd`, `salt`, `name`, `surname`) VALUES
(1, 'active', 'admin@example.com', '28d6c035e66a55a3a060031ac8fde64b304649d9', 'c0a61364c2c197103db85edc63d645701f9fb040', 'Default', 'Admin');

-- @2 grupa admininstratorów
INSERT INTO `groups` (`group_id`, `name`, `project_id`, `desc`) VALUES (1, 'Administratorzy', NULL, 'grupa administratorów serwisu');

-- @3 uprawnienia dla adminów
INSERT INTO `group_privileges` (`group_id`, `privilege`) VALUES (1, 'adm');

-- @4 przydzielenie admina do grupy
INSERT INTO `user_groups` (`user_id`, `group_id`) VALUES (1, 1);


-- @revert


-- brak
