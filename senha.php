<?php
echo password_hash('P@ssw0rd', PASSWORD_DEFAULT);

//@dministrad0r


// insert na tabela role
// INSERT INTO role (name, description) VALUES 
// ('user', 'Usuário padrão do sistema com permissões limitadas'),
// ('admin', 'Administrador com acesso total ao sistema');


//insert na tabela user

// INSERT INTO lojacriartdb.user 
// (name, email, passwordHash, phone, cpf, createdAt, isActive, Role_idRole) 
// VALUES 
// (
//  'Nome do Admin', 
//  'admin@email.com', 
//  '$2y$10$rZS93ildl2n1bDquDSD63O/Ctn4MsJhDwAGKqrjWHjiNro2bA8cGe', 
//  '12999999999', 
//  '44444444400', 
//  NOW(), 
//  1, 
//  2 
// );
