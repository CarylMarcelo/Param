<?php
require_once __DIR__ . '/../config/database.php';
class CustomerServiceController
{
    public static function summary(): array
    {
        $db=getDbConnection(); return ['open'=>(int)$db->query("SELECT COUNT(*) FROM support_concerns WHERE status='open'")->fetchColumn(),'in_progress'=>(int)$db->query("SELECT COUNT(*) FROM support_concerns WHERE status='in_progress'")->fetchColumn(),'resolved'=>(int)$db->query("SELECT COUNT(*) FROM support_concerns WHERE status='resolved'")->fetchColumn(),'pending_refunds'=>(int)$db->query("SELECT COUNT(*) FROM refund_requests WHERE status='pending'")->fetchColumn()];
    }
    public static function concerns(): array
    {
        return getDbConnection()->query("SELECT sc.concern_id, sc.order_id, CONCAT_WS(' ',u.first_name,u.middle_name,u.last_name,u.suffix) customer_name, u.email, (SELECT contact_number FROM user_contacts WHERE user_id=u.user_id ORDER BY is_primary DESC,contact_id LIMIT 1) phone, sc.subject,sc.message,sc.response,sc.status,sc.created_at,o.order_status,o.total_amount FROM support_concerns sc JOIN users u ON u.user_id=sc.customer_id LEFT JOIN orders o ON o.order_id=sc.order_id ORDER BY FIELD(sc.status,'open','in_progress','resolved','closed'),sc.created_at DESC")->fetchAll();
    }
    public static function updateConcern(int $id,int $actor,array $input): array
    {
        $status=strtolower((string)($input['status']??'')); if(!in_array($status,['open','in_progress','resolved','closed'],true)){http_response_code(422);return['error'=>'Invalid concern status'];}
        $stmt=getDbConnection()->prepare('UPDATE support_concerns SET response=:response,status=:status,assigned_to_user_id=:actor WHERE concern_id=:id'); $stmt->execute(['response'=>trim((string)($input['response']??''))?:null,'status'=>$status,'actor'=>$actor,'id'=>$id]);
        if(!$stmt->rowCount()){http_response_code(404);return['error'=>'Concern not found'];} self::audit($actor,'support.reply','support_concerns',$id,'Updated support concern #'.$id.' to '.str_replace('_',' ',$status)); return['success'=>true];
    }
    public static function requestRefund(int $concernId,int $actor,string $reason,string $notes): array
    {
        $db=getDbConnection(); $stmt=$db->prepare('SELECT order_id FROM support_concerns WHERE concern_id=:id');$stmt->execute(['id'=>$concernId]);$orderId=$stmt->fetchColumn();
        if(!$orderId){http_response_code(422);return['error'=>'This concern is not linked to an order'];}
        $existing=$db->prepare("SELECT 1 FROM refund_requests WHERE order_id=:order AND status IN ('pending','approved') LIMIT 1");$existing->execute(['order'=>$orderId]);if($existing->fetchColumn()){http_response_code(409);return['error'=>'An active refund request already exists for this order'];}
        $insert=$db->prepare('INSERT INTO refund_requests(order_id,requested_by_user_id,reason,customer_service_notes)VALUES(:order,:actor,:reason,:notes)');$insert->execute(['order'=>$orderId,'actor'=>$actor,'reason'=>trim($reason),'notes'=>trim($notes)?:null]);$id=(int)$db->lastInsertId();self::audit($actor,'refund.request','refund_requests',$id,'Requested refund review for order #'.$orderId);return['refund_request_id'=>$id];
    }
    public static function refunds(int $actor): array
    {
        $stmt=getDbConnection()->prepare('SELECT rr.refund_request_id,rr.order_id,rr.reason,rr.customer_service_notes,rr.admin_notes,rr.status,rr.requested_at FROM refund_requests rr WHERE rr.requested_by_user_id=:actor ORDER BY rr.requested_at DESC');$stmt->execute(['actor'=>$actor]);return$stmt->fetchAll();
    }
    private static function audit(int $user,string $action,string $table,int $record,string $details):void{$stmt=getDbConnection()->prepare('INSERT INTO audit_logs(user_id,action_name,table_name,record_id,details)VALUES(:user,:action,:table_name,:record,:details)');$stmt->execute(compact('user','action','record','details')+['table_name'=>$table]);}
}
