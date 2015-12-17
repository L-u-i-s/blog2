CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    username CHAR(128) NOT NULL,
    password CHAR(128) NOT NULL,
    email CHAR(128) NOT NULL
);

CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    title CHAR(128) NOT NULL,
    description TEXT NOT NULL,
    content TEXT NOT NULL,
    status INTEGER NOT NULL,
    author_id INTEGER
);
    INSERT INTO posts (id, title, description, content, status) VALUES (
        NULL, 
        'First post', 
        'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore, veritatis, tempora, necessitatibus inventore nisi quam quia repellat ut tempore laborum possimus eum dicta id animi corrupti debitis ipsum officiis rerum.',
        '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero, obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia corporis eligendi eos magni recusandae laborum minus inventore?</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, tenetur natus doloremque laborum quos iste ipsum rerum obcaecati impedit odit illo dolorum ab tempora nihil dicta earum fugiat. Temporibus, voluptatibus.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos, doloribus, dolorem iusto blanditiis unde eius illum consequuntur neque dicta incidunt ullam ea hic porro optio ratione repellat perspiciatis. Enim, iure!</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, nostrum, aliquid, animi, ut quas placeat totam sunt tempora commodi nihil ullam alias modi dicta saepe minima ab quo voluptatem obcaecati?</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum, dolor quis. Sunt, ut, explicabo, aliquam tenetur ratione tempore quidem voluptates cupiditate voluptas illo saepe quaerat numquam recusandae? Qui, necessitatibus, est!</p>',
        1
    );
    INSERT INTO posts (id, title, description, content, status) VALUES (NULL, 'Some interesting post', 'This is an interesting post, I sware!!', 'Nothing to see here.', 0);


CREATE TABLE comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    author CHAR(128),
    email CHAR(128),
    message TEXT NOT NULL,
    status INTEGER NOT NULL,
    post_id INTEGER NOT NULL
);
    INSERT INTO comments (id, author, email, message, status, post_id) VALUES (
    NULL,
    'Some guy',
    'guy@somewhere.com',
    'Hey, some guy has left a comment!',
    1,
    1
    );
    INSERT INTO comments (id, author, email, message, status, post_id) VALUES (
    NULL,
    'Second guy',
    'guy2@somewhere.com',
    'Hey, some guy2 has left a comment!',
    0,
    2
    );

CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name CHAR(128) NOT NULL
);

CREATE TABLE lookup (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    type CHAR(128) NOT NULL,
	code INTEGER NOT NULL,
	value CHAR(128)
);

INSERT INTO lookup 	(id, type, code, value) VALUES (NULL, 'post.status', 0, 'draft');
INSERT INTO lookup 	(id, type, code, value) VALUES (NULL, 'post.status', 1, 'published');
INSERT INTO lookup 	(id, type, code, value) VALUES (NULL, 'post.status', 2, 'archived');