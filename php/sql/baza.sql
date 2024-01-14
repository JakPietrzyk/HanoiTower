CREATE TABLE User (
    user_id INTEGER PRIMARY KEY,
    username TEXT,
    password TEXT
);

CREATE TABLE UserPreferences (
    user_id INTEGER PRIMARY KEY,
    numberOfDisks INTEGER,
    animationSpeed INTEGER,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

CREATE TRIGGER after_insert_user
AFTER INSERT ON User
FOR EACH ROW
BEGIN
    INSERT INTO UserPreferences (user_id, numberOfDisks, animationSpeed) VALUES (NEW.user_id, 3, 5);
END;
