CREATE TABLE `log_accessi` (
  `id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `mail_immessa` varchar(50) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accesso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `log_accessi`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `log_accessi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


  
CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `selector` varchar(12) NOT NULL,
  `hashedvalidator` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `expires` timestamp NULL DEFAULT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`);

ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
  

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `stato` int(11) NOT NULL,
  `reset_selector` varchar(100) NOT NULL,
  `reset_code` varchar(256) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;