/*
	add games to the gamelibrary table after check if exist
*/

DROP PROCEDURE IF EXISTS addgametolibrary;


DELIMITER $$
CREATE PROCEDURE addgametolibrary(gid int, uid int, genre varchar(30), platform varchar(30), name varchar(50))
BEGIN
  IF (SELECT COUNT(*) FROM gamelibrary WHERE gameID=gid AND userID=uid)=0 THEN
  INSERT INTO gamelibrary (gameID, userID, genre, platform, name) VALUES ( gid, uid ,genre, platform, name); 
  END IF;
END;
$$
DELIMITER ;

CALL addgametolibrary(99, 2);


CREATE VIEW userNamesDndID AS
SELECT userdata.id, members.username, members.email, userdata.firstname, userdata.lastname, userdata.rating, userdata.city, userdata.platforms, userdata.img
FROM members, userdata
WHERE members.id = userdata.id;


CREATE VIEW userAndGamelibrary AS
SELECT userdata.id, members.username, userdata.firstname, userdata.lastname, gamelibrary.gameID, gamelibrary.genre, gamelibrary.platform, gamelibrary.name, gamelibrary.lentdate, gamelibrary.userborrowid, gamelibrary.status
FROM members, userdata, gamelibrary
WHERE members.id = userdata.id AND members.id = gamelibrary.userID AND userdata.id = gamelibrary.userID;

CREATE VIEW userAndBorrowedGames AS
SELECT userdata.id, members.username, userdata.firstname, userdata.lastname, gamelibrary.gameID, gamelibrary.genre, gamelibrary.platform, gamelibrary.name, gamelibrary.lentdate, gamelibrary.userID, gamelibrary.status
FROM members, userdata, gamelibrary
WHERE members.id = userdata.id AND members.id = gamelibrary.userborrowid AND userdata.id = gamelibrary.userborrowid;