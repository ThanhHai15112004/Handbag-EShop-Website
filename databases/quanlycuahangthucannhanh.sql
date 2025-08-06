Create table [categories] (
	[id_categories] Integer Identity NOT NULL,
	[name] Nvarchar(10) NOT NULL,
	[icon_image] Varchar(255) NULL,
	[is_active] Bit NOT NULL,
Constraint [pk_categories] Primary Key  ([id_categories])
) 
go

Create table [products] (
	[id_products] Integer Identity NOT NULL,
	[id_categories] Integer NOT NULL,
	[name] Nvarchar(100) NOT NULL,
	[price] Decimal(10,0) NOT NULL,
	[description] Nvarchar(255) NULL,
	[is_available] Bit NOT NULL,
	[created_at] Datetime NULL,
	[updated_at] Datetime NULL,
Constraint [pk_products] Primary Key  ([id_products])
) 
go

Create table [product_images] (
	[id_product_images] Integer Identity NOT NULL,
	[id_products] Integer NOT NULL,
	[image_url] Varchar(255) NOT NULL,
	[is_main] Bit NOT NULL,
Constraint [pk_product_images] Primary Key  ([id_product_images])
) 
go

Create table [product_nutrition] (
	[id_product_nutrition] Integer Identity NOT NULL,
	[id_products] Integer NOT NULL,
	[calo] Nvarchar(50) NULL,
	[protein] Nvarchar(50) NULL,
	[carbohydrate] Nvarchar(50) NULL,
	[fat] Nvarchar(50) NULL,
Constraint [pk_product_nutrition] Primary Key  ([id_product_nutrition])
) 
go

Create table [promotions] (
	[id_promotions] Integer Identity NOT NULL,
	[code] Varchar(50) NOT NULL,
	[name] Nvarchar(255) NOT NULL,
	[discount_type] Bit NOT NULL,
	[value] Decimal(10,0) NOT NULL,
	[start_date] Datetime NOT NULL,
	[end_date] Datetime NOT NULL,
	[is_active] Bit NOT NULL,
Constraint [pk_promotions] Primary Key  ([id_promotions])
) 
go

Create table [promotion_product] (
	[id_promotion_product] Integer Identity NOT NULL,
	[id_products] Integer NULL,
	[id_promotions] Integer NOT NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_promotion_product] Primary Key  ([id_promotion_product])
) 
go

Create table [users] (
	[id_users] Integer Identity NOT NULL,
	[email] Varchar(255) NOT NULL,
	[password] Nvarchar(100) NOT NULL,
	[full_name] Nvarchar(100) NOT NULL,
	[phone] Varchar(15) NOT NULL,
	[address] Nvarchar(255) NOT NULL,
	[role] Bit NOT NULL,
	[created_at] Datetime NULL,
	[updated_at] Datetime NULL,
	[avatar_url] Varchar(255) NULL,
	[last_login_at] Datetime NULL,
Constraint [pk_users] Primary Key  ([id_users])
) 
go

Create table [carts] (
	[id_cart] Integer Identity NOT NULL,
	[id_users] Integer NULL,
	[session_id] Varchar(50) NULL,
	[created_at] Datetime NULL,
	[updated_at] Datetime NULL,
	[total_quantity] Integer NULL,
	[total_price] Decimal(10,0) NULL,
Constraint [pk_carts] Primary Key  ([id_cart])
) 
go

Create table [cart_items] (
	[id_cart_items] Integer Identity NOT NULL,
	[id_products] Integer NULL,
	[id_cart] Integer NULL,
	[quantity] Integer NULL,
	[price] Decimal(10,0) NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_cart_items] Primary Key  ([id_cart_items])
) 
go

Create table [orders] (
	[id_orders] Integer Identity NOT NULL,
	[id_users] Integer NOT NULL,
	[total_price] Decimal(10,0) NULL,
	[total_quantity] Integer NULL,
	[status] Bit NULL,
	[created_at] Datetime NULL,
	[updated_at] Datetime NULL,
Constraint [pk_orders] Primary Key  ([id_orders])
) 
go

Create table [order_items] (
	[id_order_items] Integer Identity NOT NULL,
	[id_orders] Integer NOT NULL,
	[id_products] Integer NOT NULL,
	[quantity] Integer NULL,
	[price] Decimal(10,0) NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_order_items] Primary Key  ([id_order_items])
) 
go

Create table [invoices] (
	[id_invoice] Integer Identity NOT NULL,
	[id_orders] Integer NOT NULL,
	[payment_method] Bit NOT NULL,
	[amount_paid] Decimal(10,0) NOT NULL,
	[issued_at] Datetime NOT NULL,
	[is_paid] Bit NOT NULL,
	[earned_points] Integer NULL,
Constraint [pk_invoices] Primary Key  ([id_invoice])
) 
go

Create table [invoice_history] (
	[id_invoice_history] Integer Identity NOT NULL,
	[id_users] Integer NOT NULL,
	[id_invoice] Integer NOT NULL,
	[action] Varchar(100) NOT NULL,
	[changed_by] Nvarchar(100) NOT NULL,
	[timestamp] Datetime NOT NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_invoice_history] Primary Key  ([id_invoice_history])
) 
go

Create table [order_status_history] (
	[id_status_history] Integer Identity NOT NULL,
	[id_users] Integer NOT NULL,
	[id_orders] Integer NOT NULL,
	[status] Bit NOT NULL,
	[changed_at] Datetime NOT NULL,
	[changed_by] Nvarchar(100) NOT NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_order_status_history] Primary Key  ([id_status_history])
) 
go

Create table [accounts] (
	[id_accounts] Integer Identity NOT NULL,
	[username] Varchar(100) NOT NULL,
	[email] Varchar(100) NOT NULL,
	[password] Nvarchar(10) NOT NULL,
	[full_name] Nvarchar(100) NOT NULL,
	[role] Bit NOT NULL,
	[is_active] Bit NOT NULL,
	[last_login_at] Datetime NULL,
	[created_at] Datetime NULL,
	[updated_at] Datetime NULL,
	[avatar_url] Varchar(255) NULL,
Constraint [pk_accounts] Primary Key  ([id_accounts])
) 
go

Create table [delivery] (
	[id_delivery] Integer Identity NOT NULL,
	[id_orders] Integer NOT NULL,
	[id_accounts] Integer NOT NULL,
	[shipping_address] Nvarchar(255) NOT NULL,
	[delivery_status] Bit NOT NULL,
	[shipped_at] Datetime NULL,
	[delivered_at] Datetime NULL,
Constraint [pk_delivery] Primary Key  ([id_delivery],[id_orders])
) 
go

Create table [delivery_status_logs] (
	[id_log] Integer Identity NOT NULL,
	[id_delivery] Integer NOT NULL,
	[id_orders] Integer NOT NULL,
	[id_users] Integer NOT NULL,
	[status] Bit NOT NULL,
	[changed_at] Datetime NOT NULL,
	[changed_by] Nvarchar(100) NOT NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_delivery_status_logs] Primary Key  ([id_log])
) 
go

Create table [membership_levels] (
	[id_level] Integer Identity NOT NULL,
	[level_name] Nvarchar(100) NOT NULL,
	[min_point] Integer NOT NULL,
	[discount_rate] Decimal(10,2) NULL,
	[note] Nvarchar(255) NULL,
Constraint [pk_membership_levels] Primary Key  ([id_level])
) 
go

Create table [user_memberships] (
	[id_memberships] Integer Identity NOT NULL,
	[id_level] Integer NOT NULL,
	[id_users] Integer NOT NULL,
	[point_balance] Integer NOT NULL,
	[updated_at] Datetime NULL,
	[joined_at] Datetime NULL,
	[total_spent] Decimal(10,0) NULL,
Constraint [pk_user_memberships] Primary Key  ([id_memberships])
) 
go

Create table [point_transactions] (
	[id_point_txn] Integer Identity NOT NULL,
	[id_memberships] Integer NOT NULL,
	[id_invoice] Integer NOT NULL,
	[points_changed] Integer NULL,
	[reason] Nvarchar(255) NULL,
	[created_at] Datetime NULL,
Constraint [pk_point_transactions] Primary Key  ([id_point_txn],[id_invoice])
) 
go


Alter table [products] add Constraint [Thuoc] foreign key([id_categories]) references [categories] ([id_categories]) 
go
Alter table [product_images] add Constraint [Co] foreign key([id_products]) references [products] ([id_products]) 
go
Alter table [product_nutrition] add Constraint [Gom] foreign key([id_products]) references [products] ([id_products]) 
go
Alter table [promotion_product] add Constraint [Duoc] foreign key([id_products]) references [products] ([id_products]) 
go
Alter table [cart_items] add Constraint [Gom] foreign key([id_products]) references [products] ([id_products]) 
go
Alter table [order_items] add Constraint [Co] foreign key([id_products]) references [products] ([id_products]) 
go
Alter table [promotion_product] add Constraint [Gom] foreign key([id_promotions]) references [promotions] ([id_promotions]) 
go
Alter table [carts] add Constraint [Co] foreign key([id_users]) references [users] ([id_users]) 
go
Alter table [orders] add Constraint [Co] foreign key([id_users]) references [users] ([id_users]) 
go
Alter table [invoice_history] add Constraint [Tra] foreign key([id_users]) references [users] ([id_users]) 
go
Alter table [order_status_history] add Constraint [Co] foreign key([id_users]) references [users] ([id_users]) 
go
Alter table [delivery_status_logs] add Constraint [Co] foreign key([id_users]) references [users] ([id_users]) 
go
Alter table [user_memberships] add Constraint [Co] foreign key([id_users]) references [users] ([id_users]) 
go
Alter table [cart_items] add Constraint [Co] foreign key([id_cart]) references [carts] ([id_cart]) 
go
Alter table [order_items] add Constraint [Gom] foreign key([id_orders]) references [orders] ([id_orders]) 
go
Alter table [invoices] add Constraint [Gom] foreign key([id_orders]) references [orders] ([id_orders]) 
go
Alter table [order_status_history] add Constraint [Gom] foreign key([id_orders]) references [orders] ([id_orders]) 
go
Alter table [delivery] add Constraint [Thuoc] foreign key([id_orders]) references [orders] ([id_orders]) 
go
Alter table [invoice_history] add Constraint [Co] foreign key([id_invoice]) references [invoices] ([id_invoice]) 
go
Alter table [point_transactions] add Constraint [Co] foreign key([id_invoice]) references [invoices] ([id_invoice]) 
go
Alter table [delivery] add Constraint [Co] foreign key([id_accounts]) references [accounts] ([id_accounts]) 
go
Alter table [delivery_status_logs] add Constraint [Gom] foreign key([id_delivery],[id_orders]) references [delivery] ([id_delivery],[id_orders]) 
go
Alter table [user_memberships] add Constraint [Gom] foreign key([id_level]) references [membership_levels] ([id_level]) 
go
Alter table [point_transactions] add Constraint [Gom] foreign key([id_memberships]) references [user_memberships] ([id_memberships]) 
go


Set quoted_identifier on
go


Set quoted_identifier off
go


