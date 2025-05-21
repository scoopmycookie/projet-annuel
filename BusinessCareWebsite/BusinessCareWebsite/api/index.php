<?php


header('Content-Type: application/json');

echo json_encode([
  'status' => 'API BusinessCare en ligne',
  'endpoints' => [
    '/api/auth/login.php',
    '/api/companies/get_all.php',
    '/api/companies/create.php',
    '/api/contracts/by_company.php',
    '/api/services/list_available.php',
    '/api/services/reserve.php',
    '/api/users/me.php',
    '/api/invoices/list_by_user.php',
  ]
]);
