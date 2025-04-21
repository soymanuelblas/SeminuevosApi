<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Bank routes
$route['api/bankaccount'] = 'BankController/add_bank_account';
$route['api/listbankaccounts'] = 'BankController/listBankAccounts';
$route['api/deletebankaccount'] = 'BankController/deleteBankAccount';
$route['api/updatebankaccount'] = 'BankController/updateBankAccount';