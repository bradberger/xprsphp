CREATE TABLE mod_xprs_users (
    email varchar(255) NOT NULL,
    nickname varchar(255) DEFAULT '',
    password varchar(255) DEFAULT '',
    vbid varchar(255) DEFAULT '',
    domain varchar(255) DEFAULT '',
    complete tinyint(1) DEFAULT 0,
    updated_at  DATETIME NULL,
    created_at DATETIME NULL,
    deleted_at  DATETIME NULL,
    PRIMARY KEY (email)
)
