-- Select the name of a creature with a given challenge rating, getCR.php
SELECT C.name FROM mm_creatures C 
WHERE C.cr = [inputCR]


-- Select the name of creatures with an attribute stat over a given value, getAttr.php

SELECT C.name FROM mm_creatures C
INNER JOIN mm_c_attr CA ON C.id = CA.creature_id
INNER JOIN mm_attributes A ON CA.attribute = A.id
WHERE A.name = [inputAttribute] AND CA.stat > [inputValue]


-- Select the name of creatures of a given type, getType.php

SELECT C.name FROM mm_creatures C
INNER JOIN mm_types T ON C.type_id = T.id
WHERE T.category = [inputType]


-- Select Creatures with a given skill, getSkill.php

SELECT C.name, CS.bonus FROM mm_creatures C
INNER JOIN mm_c_skills CS ON C.id = CS.creature_id
INNER JOIN mm_skills S ON CS.skill_id = S.id
WHERE S.name = [inputSkill]


-- Select creatures found in a given environment, getEnviron.php

SELECT C.name FROM mm_creatures C
INNER JOIN mm_c_environ CE ON C.id = CE.creature_id
INNER JOIN mm_environments E ON CE.envir_id = E.id
WHERE E.name = [inputEnvironment]


-- Select creatures with a given sense, getSense.php

SELECT C.name, CS.distance FROM mm_creatures C
INNER JOIN mm_c_senses CS ON C.id = CS.creature_id
INNER JOIN mm_senses S ON CS.sense_id = S.id
WHERE S.name = [inputSense]

-- Select minions of a given creature, getMinion.php
SELECT CM.name FROM mm_creatures CB
INNER JOIN mm_minions M ON CB.id = M.boss_id
INNER JOIN mm_creatures CM ON M.minion_id = CM.id
WHERE CB.name = [inputCreature] 

-- Select Hit Points, Armor Class, Challenge Rating, and Type of a given creature, display.php
SELECT C.hp, C.ac, C.cr, T.category FROM mm_creatures C
INNER JOIN mm_types T ON C.type_id = T.id
WHERE C.name = [inputCreature]

-- Select Attributes and associated stat for a given creature,
-- ordered by id to maintain traditional attribute order, display.php
SELECT A.name, CA.stat FROM mm_creatures C
INNER JOIN mm_c_attr CA ON C.id = CA.creature_id
INNER JOIN mm_attributes A ON CA.attribute = A.id
WHERE C.name = [inputCreature]
ORDER BY A.id

-- Select Skills and associated bonus for a given creature, display.php
SELECT S.name, CS.bonus FROM mm_creatures C
INNER JOIN mm_c_skills CS ON C.id = CS.creature_id
INNER JOIN mm_skills S ON CS.skill_id = S.id
WHERE C.name = [inputCreature]

-- Select Senses and associated ranges for a given creature, display.php
SELECT S.name, CS.distance FROM mm_creatures C
INNER JOIN mm_c_senses CS ON C.id = CS.creature_id
INNER JOIN mm_senses S ON CS.sense_id = S.id
WHERE C.name = [inputCreature]

-- Select Environments you can find a given creature, display.php
SELECT E.name FROM mm_creatures C
INNER JOIN mm_c_environ CE ON C.id = CE.creature_id
INNER JOIN mm_environments E ON CE.envir_id = E.id
WHERE C.name = [inputCreature]

-- Select the minions of a give creature, display.php
SELECT CM.name FROM mm_creatures CB
INNER JOIN mm_minions M ON CB.id = M.boss_id
INNER JOIN mm_creatures CM ON M.minion_id = CM.id
WHERE CB.name = [inputCreature]

-- Select potential bosses of a given creature, display.php
SELECT CB.name FROM mm_creatures CB
INNER JOIN mm_minions M ON CB.id = M.boss_id
WHERE M.minion_id = (SELECT id FROM mm_creatures WHERE name = ?)



-- Select the contents of each non relational table to fill drop
-- downs on MonsterDisplay.html and MonsterEdit.html, dropdown.php

SELECT name FROM mm_attributes

SELECT name FROM mm_creatures

SELECT category FROM mm_types

SELECT name FROM mm_skills

SELECT name FROM mm_senses

SELECT name FROM mm_environments


-- Add a new attribute to the database, addAttribute.php
INSERT INTO mm_attributes(name) VALUES ([inputName])


-- Add a new creature to the database, update if one with 
-- the same name already exists, addCreature.php
INSERT INTO mm_creatures(name, hp, ac, cr, type_id) 
VALUES ([name], [hp], [ac], [cr], (SELECT id FROM mm_types WHERE category = [type_name]))
ON DUPLICATE KEY UPDATE hp = [hp], ac = [ac], cr = [cr], type_id = (SELECT id FROM mm_types WHERE category = [type_name])

-- Add a new relationship between creature and attribute 
-- with a given value, update value if creature and attribute
-- matches an existing row, addCreatureAttr.php
INSERT INTO mm_c_attr (creature_id, attribute, stat)
VALUES
	((SELECT id FROM mm_creatures WHERE name= [creatureName]),
	(SELECT id FROM mm_attributes WHERE name= [attributeName]),
	[stat])
ON DUPLICATE KEY UPDATE stat = [stat]

-- add a relationship between a creature and an environment, addCreatureEnviron.php
INSERT INTO mm_c_environ (creature_id, envir_id)
VALUES ((SELECT id FROM mm_creatures WHERE name= [creatureName]),
        (SELECT id FROM mm_environments WHERE name= [environmentName]))

-- add a relationship between a sense and a creature, with a range
-- update range if relationship already exists. 		
INSERT INTO mm_c_senses (creature_id, sense_id, distance)
VALUES((SELECT id FROM mm_creatures WHERE name= [creatureName]),
	   (SELECT id FROM mm_senses WHERE name= [senseName]),
	   [range])
ON DUPLICATE KEY UPDATE distance = [range]

-- add a relationship between a creature and a skill with a bonus value,
-- update bonus value if relationship already exists, addCreatureSkill.php
INSERT INTO mm_c_skills (creature_id, skill_id, bonus)
VALUES ((SELECT id FROM mm_creatures WHERE name= [creatureName]),
		(SELECT id FROM mm_skills WHERE name= [skillName]),
		[bonus])
ON DUPLICATE KEY UPDATE bonus = [bonus]

-- add an environment to the database, addEnvironment.php
INSERT INTO mm_environments(name) VALUES ([environmentName])

-- add a minion relationship between two creatures, addMinion.php
INSERT INTO mm_minions(boss_id, minion_id) 
VALUES ((SELECT id FROM mm_creatures WHERE name = [bossName]]), 
		(SELECT id FROM mm_creatures WHERE name = [minionName]))

-- add a sense to the database, addSense.php
INSERT INTO mm_senses(name) VALUES ([senseName])

-- add a skill and its associated attribute to the database
-- update the attribute if the skill already exists, addSkill.php
INSERT INTO mm_skills(name, attribute) 
VALUES ([skillName], (SELECT id FROM mm_attributes WHERE name = [attributeName]))
ON DUPLICATE KEY UPDATE attribute = (SELECT id FROM mm_attributes WHERE name = [attributeName])

-- add a type to the database, addType.php
INSERT INTO mm_types(category) VALUES ([typeName])

-- delete an attribute from the database, delAttribute.php
DELETE FROM mm_attributes WHERE name = [attributeName] LIMIT 1

-- remove a creature from the database, delCreature.php
DELETE FROM mm_creatures WHERE name = [creatureName] LIMIT 1

-- remove an environment from the database, delEnviron.php
DELETE FROM mm_environments WHERE name = [environmentName]

-- remove a sense from the database, delSense.php
DELETE FROM mm_senses WHERE name = [senseName]

-- remove a skill from the database, delSkill.php
DELETE FROM mm_skills WHERE name = [skillName]

-- remove a type from the table
DELETE FROM mm_types WHERE category = [typeName]

-- remove all attribute-creature relationships for a given creature, remCAttrs.php
DELETE FROM mm_c_attr WHERE creature_id = (SELECT id FROM mm_creatures WHERE name= [creatureName])

-- remove all environment-creature relationships for a given creature, remCEnvirs.php
DELETE FROM mm_c_environ WHERE creature_id = (SELECT id FROM mm_creatures WHERE name= [creatureName])

-- remove all minion relationships for a given creature, remCMinions.php
DELETE FROM mm_minions WHERE boss_id = (SELECT id FROM mm_creatures WHERE name= [creatureName])

-- remove all creature-sense relationships for a given creature, remCSenses.php
DELETE FROM mm_c_senses WHERE creature_id = (SELECT id FROM mm_creatures WHERE name= [creatureName])

-- remove all creature-sense relationships for a given creature, remCSkills.php
DELETE FROM mm_c_skills WHERE creature_id = (SELECT id FROM mm_creatures WHERE name= [creatureName])

