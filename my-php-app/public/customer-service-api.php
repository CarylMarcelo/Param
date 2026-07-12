<?php
require_once __DIR__.'/../src/middleware/authentication.php';require_once __DIR__.'/../src/middleware/rbacmiddleware.php';require_once __DIR__.'/../src/controllers/customer-service-controller.php';header('Content-Type: application/json');set_exception_handler(function(){http_response_code(500);echo json_encode(['error'=>'Server request failed']);});
$user=requireLoginOrJson401();$resource=$_GET['resource']??'';$method=$_SERVER['REQUEST_METHOD'];$id=isset($_GET['id'])?(int)$_GET['id']:null;if(!in_array($method,['GET','HEAD'],true))requireValidCsrfToken();$input=json_decode(file_get_contents('php://input'),true)??[];
if($resource==='summary'&&$method==='GET'){requirePermission($user,'support.view');echo json_encode(CustomerServiceController::summary());}
elseif($resource==='concerns'&&$method==='GET'){requirePermission($user,'support.view');requirePermission($user,'orders.view_support');requirePermission($user,'customers.view_support_info');echo json_encode(CustomerServiceController::concerns());}
elseif($resource==='concerns'&&$method==='PUT'&&$id){requirePermission($user,'support.reply');echo json_encode(CustomerServiceController::updateConcern($id,(int)$user['user_id'],$input));}
elseif($resource==='refunds'&&$method==='GET'){requirePermission($user,'refunds.request');echo json_encode(CustomerServiceController::refunds((int)$user['user_id']));}
elseif($resource==='refunds'&&$method==='POST'&&$id){requirePermission($user,'refunds.request');echo json_encode(CustomerServiceController::requestRefund($id,(int)$user['user_id'],(string)($input['reason']??''),(string)($input['notes']??'')));}
else{http_response_code(404);echo json_encode(['error'=>'Unknown resource']);}
