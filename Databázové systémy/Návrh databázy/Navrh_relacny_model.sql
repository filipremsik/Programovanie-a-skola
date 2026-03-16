CREATE TABLE "user" (
  "user_id" integer PRIMARY KEY NOT NULL,
  "user_nick" varchar(50) NOT NULL,
  "user_password" varchar(50) NOT NULL
);

CREATE TABLE "team_user" (
  "team_user_id" integer PRIMARY KEY NOT NULL,
  "status" boolean NOT NULL,
  "user_id" integer NOT NULL,
  "team_id" integer NOT NULL
);

CREATE TABLE "team" (
  "team_id" integer PRIMARY KEY NOT NULL,
  "team_name" varchar(50) NOT NULL,
  "chat_id" integer NOT NULL
);

CREATE TABLE "special_creep_location" (
  "special_creep_location_id" integer PRIMARY KEY NOT NULL,
  "location_id" integer NOT NULL,
  "special_creep_id" integer NOT NULL
);

CREATE TABLE "special_creep" (
  "special_creep_id" integer PRIMARY KEY NOT NULL,
  "creep_id" integer NOT NULL,
  "level_id" integer NOT NULL,
  "quest_id" integer NOT NULL,
  "expirience_reward" integer NOT NULL,
  "health" integer NOT NULL,
  "strength" integer NOT NULL,
  "defence" integer NOT NULL
);

CREATE TABLE "role" (
  "role_id" integer PRIMARY KEY NOT NULL,
  "role_name" varchar(50) NOT NULL
);

CREATE TABLE "registration" (
  "registration_id" integer PRIMARY KEY NOT NULL,
  "registration_type" varchar(50) NOT NULL
);

CREATE TABLE "quest_location" (
  "quest_location" integer PRIMARY KEY NOT NULL,
  "quest_id" integer NOT NULL,
  "location_id" integer NOT NULL
);

CREATE TABLE "quest_item" (
  "quest_item_id" integer PRIMARY KEY NOT NULL,
  "item_id" integer NOT NULL,
  "quest_id" integer NOT NULL
);

CREATE TABLE "quest" (
  "quest_id" integer PRIMARY KEY NOT NULL,
  "creep_to_kill" integer NOT NULL,
  "character_id" integer NOT NULL,
  "creep_id" integer NOT NULL,
  "experience_reward" integer NOT NULL
);

CREATE TABLE "place" (
  "place_id" integer PRIMARY KEY NOT NULL,
  "place_name" varchar(50) NOT NULL
);

CREATE TABLE "non_registered_registration" (
  "non_registered_registration" integer PRIMARY KEY NOT NULL,
  "user_id" integer NOT NULL,
  "registration_id" integer NOT NULL,
  "confirmed" boolean NOT NULL
);

CREATE TABLE "location" (
  "location_id" integer PRIMARY KEY NOT NULL,
  "place_id" integer NOT NULL,
  "x" bigint NOT NULL,
  "y" bigint NOT NULL
);

CREATE TABLE "level" (
  "level_id" integer PRIMARY KEY NOT NULL,
  "experience_to_upgrade" integer NOT NULL,
  "upgrage_health" integer NOT NULL,
  "upgrade_defence" integer NOT NULL,
  "upgrade_strength" integer NOT NULL
);

CREATE TABLE "item_location" (
  "item_location_id" integer PRIMARY KEY NOT NULL,
  "location_id" integer NOT NULL,
  "item_id" integer NOT NULL
);

CREATE TABLE "item" (
  "item_id" integer PRIMARY KEY NOT NULL,
  "item_health" integer NOT NULL,
  "item_strength" integer NOT NULL,
  "item_defence" integer NOT NULL,
  "item_name" varchar(50) NOT NULL
);

CREATE TABLE "inventory_item" (
  "inventory_item_id" integer PRIMARY KEY NOT NULL,
  "item_id" integer NOT NULL,
  "inventory_id" integer NOT NULL
);

CREATE TABLE "inventory" (
  "inventory_id" integer PRIMARY KEY NOT NULL,
  "slots" integer NOT NULL,
  "character_id" integer NOT NULL
);

CREATE TABLE "game" (
  "game_id" integer PRIMARY KEY NOT NULL,
  "user_id" integer NOT NULL
);

CREATE TABLE "friendlist" (
  "friendlist_id" integer PRIMARY KEY NOT NULL,
  "user_id" integer NOT NULL,
  "friend_id" integer NOT NULL
);

CREATE TABLE "friend" (
  "friend_id" integer PRIMARY KEY NOT NULL,
  "status" boolean NOT NULL,
  "chat_id" integer NOT NULL
);

CREATE TABLE "fight_creep" (
  "character_creep_id" integer PRIMARY KEY NOT NULL,
  "experience_reward" integer NOT NULL,
  "character_id" integer NOT NULL,
  "creep_id" integer NOT NULL
);

CREATE TABLE "equiped_items" (
  "equiped_items_id" integer PRIMARY KEY NOT NULL,
  "inventory_id" integer NOT NULL,
  "slots" integer NOT NULL
);

CREATE TABLE "creep_location" (
  "creep_location_id" integer PRIMARY KEY NOT NULL,
  "creep_id" integer NOT NULL,
  "location_id" integer NOT NULL
);

CREATE TABLE "creep" (
  "creep_id" integer PRIMARY KEY NOT NULL,
  "health" integer NOT NULL,
  "level_id" integer NOT NULL,
  "strength" integer NOT NULL,
  "defence" integer NOT NULL,
  "creep_name" varchar(50) NOT NULL
);

CREATE TABLE "combat_log_character" (
  "combat_log_character_id" integer PRIMARY KEY NOT NULL,
  "combat_log_id" integer NOT NULL,
  "character_id" integer NOT NULL
);

CREATE TABLE "combat_log" (
  "combat_log_id" integer PRIMARY KEY NOT NULL,
  "fight_time" timestamp NOT NULL,
  "winner" boolean NOT NULL
);

CREATE TABLE "chat" (
  "chat_id" integer PRIMARY KEY NOT NULL,
  "massage" varchar(50) NOT NULL
);

CREATE TABLE "character_location" (
  "character_location_id" integer PRIMARY KEY NOT NULL,
  "character_id" integer NOT NULL,
  "location_id" integer NOT NULL
);

CREATE TABLE "character" (
  "character_id" integer PRIMARY KEY NOT NULL,
  "game_id" integer NOT NULL,
  "level_id" integer NOT NULL,
  "role_id" integer NOT NULL,
  "health" integer NOT NULL,
  "strength" integer NOT NULL,
  "defence" integer NOT NULL,
  "experience" integer NOT NULL,
  "alive" boolean NOT NULL,
  "name" varchar(50) NOT NULL
);

CREATE TABLE "ability_tree" (
  "ability_id" integer PRIMARY KEY NOT NULL,
  "ability_name" varchar(50) NOT NULL
);

CREATE TABLE "ability_level" (
  "Ability_level_id" integer PRIMARY KEY NOT NULL,
  "ability_choice_id" integer NOT NULL,
  "level_id" integer NOT NULL
);

CREATE TABLE "ability_choice" (
  "ability_choice_id" integer PRIMARY KEY NOT NULL,
  "ability_id" integer NOT NULL,
  "ability_parrent_id" integer NOT NULL
);

ALTER TABLE "team_user" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("user_id");

ALTER TABLE "team_user" ADD FOREIGN KEY ("team_id") REFERENCES "team" ("team_id");

ALTER TABLE "team" ADD FOREIGN KEY ("chat_id") REFERENCES "chat" ("chat_id");

ALTER TABLE "special_creep_location" ADD FOREIGN KEY ("location_id") REFERENCES "location" ("location_id");

ALTER TABLE "special_creep_location" ADD FOREIGN KEY ("special_creep_id") REFERENCES "special_creep" ("special_creep_id");

ALTER TABLE "special_creep" ADD FOREIGN KEY ("creep_id") REFERENCES "creep" ("creep_id");

ALTER TABLE "special_creep" ADD FOREIGN KEY ("level_id") REFERENCES "level" ("level_id");

ALTER TABLE "special_creep" ADD FOREIGN KEY ("quest_id") REFERENCES "quest" ("quest_id");

ALTER TABLE "quest_location" ADD FOREIGN KEY ("quest_id") REFERENCES "quest" ("quest_id");

ALTER TABLE "quest_location" ADD FOREIGN KEY ("location_id") REFERENCES "location" ("location_id");

ALTER TABLE "quest_item" ADD FOREIGN KEY ("item_id") REFERENCES "item" ("item_id");

ALTER TABLE "quest_item" ADD FOREIGN KEY ("quest_id") REFERENCES "quest" ("quest_id");

ALTER TABLE "quest" ADD FOREIGN KEY ("character_id") REFERENCES "character" ("character_id");

ALTER TABLE "quest" ADD FOREIGN KEY ("creep_id") REFERENCES "creep" ("creep_id");

ALTER TABLE "non_registered_registration" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("user_id");

ALTER TABLE "non_registered_registration" ADD FOREIGN KEY ("registration_id") REFERENCES "registration" ("registration_id");

ALTER TABLE "location" ADD FOREIGN KEY ("place_id") REFERENCES "place" ("place_id");

ALTER TABLE "item_location" ADD FOREIGN KEY ("location_id") REFERENCES "location" ("location_id");

ALTER TABLE "item_location" ADD FOREIGN KEY ("item_id") REFERENCES "item" ("item_id");

ALTER TABLE "inventory_item" ADD FOREIGN KEY ("item_id") REFERENCES "item" ("item_id");

ALTER TABLE "inventory_item" ADD FOREIGN KEY ("inventory_id") REFERENCES "inventory" ("inventory_id");

ALTER TABLE "inventory" ADD FOREIGN KEY ("character_id") REFERENCES "character" ("character_id");

ALTER TABLE "game" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("user_id");

ALTER TABLE "friendlist" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("user_id");

ALTER TABLE "friendlist" ADD FOREIGN KEY ("friend_id") REFERENCES "friend" ("friend_id");

ALTER TABLE "friend" ADD FOREIGN KEY ("chat_id") REFERENCES "chat" ("chat_id");

ALTER TABLE "fight_creep" ADD FOREIGN KEY ("character_id") REFERENCES "character" ("character_id");

ALTER TABLE "fight_creep" ADD FOREIGN KEY ("creep_id") REFERENCES "creep" ("creep_id");

ALTER TABLE "equiped_items" ADD FOREIGN KEY ("inventory_id") REFERENCES "inventory" ("inventory_id");

ALTER TABLE "creep_location" ADD FOREIGN KEY ("creep_id") REFERENCES "creep" ("creep_id");

ALTER TABLE "creep_location" ADD FOREIGN KEY ("location_id") REFERENCES "location" ("location_id");

ALTER TABLE "creep" ADD FOREIGN KEY ("level_id") REFERENCES "level" ("level_id");

ALTER TABLE "combat_log_character" ADD FOREIGN KEY ("combat_log_id") REFERENCES "combat_log" ("combat_log_id");

ALTER TABLE "combat_log_character" ADD FOREIGN KEY ("character_id") REFERENCES "character" ("character_id");

ALTER TABLE "character_location" ADD FOREIGN KEY ("character_id") REFERENCES "character" ("character_id");

ALTER TABLE "character_location" ADD FOREIGN KEY ("location_id") REFERENCES "location" ("location_id");

ALTER TABLE "character" ADD FOREIGN KEY ("game_id") REFERENCES "game" ("game_id");

ALTER TABLE "character" ADD FOREIGN KEY ("level_id") REFERENCES "level" ("level_id");

ALTER TABLE "character" ADD FOREIGN KEY ("role_id") REFERENCES "role" ("role_id");

ALTER TABLE "ability_level" ADD FOREIGN KEY ("ability_choice_id") REFERENCES "ability_choice" ("ability_choice_id");

ALTER TABLE "ability_level" ADD FOREIGN KEY ("level_id") REFERENCES "level" ("level_id");

ALTER TABLE "ability_choice" ADD FOREIGN KEY ("ability_id") REFERENCES "ability_tree" ("ability_id");

CREATE INDEX "FK_52" ON "team_user" ("team_id");

CREATE INDEX "FK_55" ON "team_user" ("user_id");

CREATE INDEX "FK_351" ON "team" ("chat_id");

CREATE INDEX "FK_224" ON "special_creep_location" ("location_id");

CREATE INDEX "FK_227" ON "special_creep_location" ("special_creep_id");

CREATE INDEX "FK_173" ON "special_creep" ("creep_id");

CREATE INDEX "FK_176" ON "special_creep" ("quest_id");

CREATE INDEX "FK_306" ON "special_creep" ("level_id");

CREATE INDEX "FK_244" ON "quest_location" ("quest_id");

CREATE INDEX "FK_247" ON "quest_location" ("location_id");

CREATE INDEX "FK_235" ON "quest_item" ("item_id");

CREATE INDEX "FK_238" ON "quest_item" ("quest_id");

CREATE INDEX "FK_144" ON "quest" ("creep_id");

CREATE INDEX "FK_147" ON "quest" ("character_id");

CREATE INDEX "FK_80" ON "non_registered_registration" ("registration_id");

CREATE INDEX "FK_87" ON "non_registered_registration" ("user_id");

CREATE INDEX "FK_187" ON "location" ("place_id");

CREATE INDEX "FK_197" ON "item_location" ("location_id");

CREATE INDEX "FK_200" ON "item_location" ("item_id");

CREATE INDEX "FK_294" ON "inventory_item" ("item_id");

CREATE INDEX "FK_297" ON "inventory_item" ("inventory_id");

CREATE INDEX "FK_262" ON "inventory" ("character_id");

CREATE INDEX "FK_93" ON "game" ("user_id");

CREATE INDEX "FK_342" ON "friendlist" ("user_id");

CREATE INDEX "FK_345" ON "friendlist" ("friend_id");

CREATE INDEX "FK_65" ON "friend" ("chat_id");

CREATE INDEX "FK_123" ON "fight_creep" ("creep_id");

CREATE INDEX "FK_126" ON "fight_creep" ("character_id");

CREATE INDEX "FK_271" ON "equiped_items" ("inventory_id");

CREATE INDEX "FK_206" ON "creep_location" ("creep_id");

CREATE INDEX "FK_209" ON "creep_location" ("location_id");

CREATE INDEX "FK_133" ON "creep" ("level_id");

CREATE INDEX "FK_356" ON "combat_log_character" ("combat_log_id");

CREATE INDEX "FK_359" ON "combat_log_character" ("character_id");

CREATE INDEX "FK_215" ON "character_location" ("character_id");

CREATE INDEX "FK_218" ON "character_location" ("location_id");

CREATE INDEX "FK_106" ON "character" ("role_id");

CREATE INDEX "FK_136" ON "character" ("level_id");

CREATE INDEX "FK_99" ON "character" ("game_id");

CREATE INDEX "FK_316" ON "ability_level" ("level_id");

CREATE INDEX "FK_331" ON "ability_level" ("ability_choice_id");

CREATE INDEX "FK_334" ON "ability_choice" ("ability_id");
