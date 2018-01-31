all password= test@123
admin@mark3.in 

company1@compnay.com
admin_company1@compnay.com
manager_company1@compnay.com
employee_company1@compnay.com


company2@company2.com
admin_company2@company2.com
manager_company2@company2.com
employee_company2@company2.com
amanager_company2@company2.com
aemployee_company2@company2.com

update users set password='$2y$08$lCwQA8FsJckH7KmiHYEp2.mfNIOuSBsFofTt6OjLvr2uQpZEm7ZBu'
delete from users where id =387;
delete from users_groups where user_id =387;
delete from profiles where user_id =387;
delete from departments where assigned_user_id =387;
delete from meetings where user_id =387;
delete from meeting_actions where user_id =387;
delete from meeting_agendas where presenter_user_id =387;
delete from meeting_users where user_id =387;
delete from notifications where user_id =387;
delete from payments where user_id =387;