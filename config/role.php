<?php

$arr = [
    'dashboard' => [
        'label' => "Dashboard",
        'access' => [
            'view' => ['admin.dashboard'],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],
    'manege_feature' => [
        'label' => "Manage Feature",
        'access' => [
            'view' => [],
            'add' => [],
            'edit' => ['admin.feature.update','admin.feature.index'],
            'delete' => [],
        ],
    ],
    'manege_role' => [
        'label' => "Manage Role",
        'access' => [
            'view' => ['admin.role'],
            'add' => ['admin.role.create'],
            'edit' => ['admin.role.update'],
            'delete' => ['admin.role.delete'],
        ],
    ],

    'manage_staff' => [
        'label' => "Manage Staff",
        'access' => [
            'view' => ['admin.role.staff'],
            'add' => ['admin.role.usersCreate'],
            'edit' => ['role.statusChange'],
            'delete' => [],
        ],
    ],
    "mange_report" => [
        'label' => "Manage Report",
        "access" => [
            "view" => [
                "admin.report.*"
            ],
            "add" => [],
            "edit" => [],
            "delete" => []
        ]
    ],
    "manage_qr_code" => [
        'label' => "Manage Qr Code",
        "access" => [
            "view" => [],
            "add" => [],
            "edit" => ["admin.qrcode.update", "admin.qrcode.image.upload",'admin.qrcode.index'],
            "delete" => []
        ]
    ],
    'supplier' => [
        'label' => "Supplier",
        'access' => [
            'view' => [
                'admin.suppliers'
            ],
            'add' => [
                'admin.supplierCreate',
                'admin.supplierStore',
            ],
            'edit' => [
                'admin.supplierEdit',
                'admin.supplierUpdate',
            ],
            'delete' => [
                'admin.supplierDelete',
            ],
        ],
    ],
    'manage_raw_items' => [
        'label' => "Manage Raw Items",
        'access' => [

            'view' => [
                'admin.rawItemList',
                'admin.purchaseRawItems',
                'admin.purchaseRawItems.search',
            ],

            'add' => [
                'admin.rawItemCreate',
                'admin.rawItemListStore',
                'admin.purchaseRawItemCreate',
                'admin.purchaseRawItemStore'
            ],

            'edit' => [
                'admin.rawItemEdit',
                'admin.rawItemUpdate',
                'admin.purchaseRawItemEdit',
                'admin.purchaseRawItemUpdate',
            ],
            'delete' => [
                'admin.rawItemDelete',
                'admin.purchaseRawItemDelete',
            ],
        ],
    ],
        'menu_items' => [
        'label' => "Manage Menu",
        'access' => [
            'view' => [
                'admin.category',
                'admin.addOnList',
                'admin.get.addOnList',
                'admin.menu',
                'admin.menuShow',
                'admin.get.menu.list',
            ],
            'add' => [
                'admin.category.create',
                'admin.category.store',
                'admin.addOnCreate',
                'admin.addOnStore',
                'admin.menuCreate',
                'admin.menuStore',
            ],
            'edit' => [
                'admin.category.edit',
                'admin.category.update',
                'admin.addOnEdit',
                'admin.addOnUpdate',
                'admin.menuEdit',
                'admin.menuUpdate',
                'admin.menu.satus.update',
            ],
            'delete' => [
                'admin.category.delete',
                'admin.addOnDelete',
                'admin.menuDelete',
            ],
        ],
    ],
    'manage_stock' => [
        'label' => "Stock",
        'access' => [
            'view' => [
                'admin.stocks',
                'admin.stock-view',
            ],
            'add' => [
                'admin.stockCreate',
                'admin.stockStore',
            ],
            'edit' => [
                'admin.stockPrimary',
                'admin.stockReleasable',
                'admin.stockEdit',
                'admin.stockUpdate',
            ],
            'delete' => [
                'admin.stockDelete',
            ],
        ],
    ],
    'manage_exports' => [
        'label' => "Export",
        'access' => [
            'view' => [
                'admin.export.log',
                'admin.export.search',
                'admin.export',
                'admin.exportfrom',
                'admin.export.store'
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],
    'manage_table' => [
        'label' => "Table",
        'access' => [
            'view' => [
                'admin.table.index',
            ],
            'add' => [
                'admin.table.create',
                'admin.table.store',
            ],
            'edit' => [
                'admin.table.edit',
                'admin.table.update'
            ],
            'delete' => [
                'admin.table.distroy'
            ],
        ],
    ],
    'manage_order' => [
        'label' => "Orders",
        'access' => [
            'view' => [
                'admin.order',
                'admin.order.show',
                'admin.order.search',
                'admin.order.receipt',
                'admin.user.orders',
                'admin.user.orderData.search',

            ],
            'add' => [
                'admin.order.create',
                'admin.order.store',
            ],
            'edit' => [
                'admin.order.accept',
                'admin.order.status',
                'admin.order.food.status',
                'admin.order.item.status',
                'admin.order.assign',

            ],
            'delete' => [],
        ],
    ],
    'reservation' => [
        'label' => "Reservation",
        'access' => [
            'view' => [
                'admin.reservation.index',
            ],
            'add' => [],
            'edit' => [
                'reservation.updateStatus',
            ],
            'delete' => [
                'admin.reservation.delete'
            ],
        ],
    ],
    'manage_coupon' => [
        'label' => "Coupon",
        'access' => [
            'view' => [
                'admin.couponList',
            ],
            'add' => [
                'admin.couponCreate',
                'admin.couponStore'
            ],
            'edit' => [
                'admin.couponEdit',
                'admin.couponUpdate'
            ],
            'delete' => [
                'admin.couponDelete'
            ],
        ],
    ],
    'manage_kitchen' => [
        'label' => "Live Kitchen",
        'access' => [
            'view' => [
                'admin.livekitchen',
                'admin.livekitchen.update'
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],
    'manage_wastage' => [
        'label' => "Wastage",
        'access' => [
            'view' => [
                'admin.wastage'
            ],
            'add' => [
                'admin.wastage.store',
                'admin.wastage.create'
            ],
            'edit' => [],
            'delete' => [],
        ]
    ],
    'manage_expense' => [
        'label' => "Expense",
        'access' => [
            'view' => [
                'admin.expense.head',
                'admin.expense',
            ],
            'add' => [
                'admin.expense.head.create',
                'admin.expense.create',
            ],
            'edit' => [
                'admin.expense.head.edit',
                'admin.expense.edit',
            ],
            'delete' => [
                'admin.expense.delete',
            ],
        ],
    ],
    'manage_chef' => [
        'label' => "Chef",
        'access' => [
            'view' => [
                'admin.chefs',
            ],
            'add' => ['admin.chefs.create','admin.chefs.store'],
            'edit' => ['admin.chefs.edit','admin.chefs.update'],
            'delete' => ['admin.chefs.destroy'],
        ],
    ],
    'manage_branch' => [
        'label' => "Branch",
        'access' => [
            'view' => [
                'admin.branches',
            ],
            'add' => ['admin.branches.create','admin.branches.store'],
            'edit' => ['admin.branches.edit','admin.branches.update'],
            'delete' => ['admin.branches.destroy'],
        ],
    ],

    'manage_service' => [
        'label' => "Service",
        'access' => [
            'view' => [
                'admin.services',
                'admin.service.category',
                'admin.service.list',
            ],
            'add' => [
                'admin.services.create',
                'admin.service.category.create',
                'admin.services.store',
                'admin.service.category.store',
            ],
            'edit' => [
                'admin.service.category.edit',
                'admin.service.category.update',
                'admin.services.edit',
                'admin.services.update',
            ],
            'delete' => [
                'admin.service.category.destroy',
                'admin.services.destroy',
            ],
        ]
    ],

    'subscriber' => [
        'label' => "Subscriber",
        'access' => [
            'view' => [
                'admin.subscriber.index',
                'admin.subscriber.sendEmail',
            ],
            'add' => [],
            'edit' => [],
            'delete' => [
                'admin.subscriber.remove'
            ],
        ],
    ],

    'shipping_control' => [
        'label' => "Shipping Control",
        'access' => [
            'view' => [
                'admin.area.index',
                'admin.get.area.list',
                'admin.delivery-man.index',
                'sendEmailToDeliveryMans',
                'admin.delivery-man.login',
            ],
            'add' => [
                'admin.area.create',
                'admin.area.store',
                'admin.delivery-man.store',
                'admin.delivery-man.create',
            ],
            'edit' => [
                    'admin.delivery-man.manage.balance',
                    'admin.area.edit',
                    'admin.area.update',
                    'admin.delivery-man.update',
                    'admin.delivery-man.edit',
                    'admin.get.dueBalance',
                    'admin.update.dueBalance',
                ],
            'delete' => ['admin.area.delete'],
        ],
    ],


    'transaction' => [
        'label' => "Transaction",
        'access' => [
            'view' => [
                'admin.transaction',
                'admin.transaction.search'
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],

    'withdraw_log' => [
        'label' => "Withdraw Log",
        'access' => [
            'view' => [
                'admin.payout.log',
                'admin.payout.pending',
                'admin.payout.search',
            ],
            'add' => [],
            'edit' => [
                'admin.payout.action'
            ],
            'delete' => [],
        ],
    ],

    'payment_log' => [
        'label' => "Payment Log",
        'access' => [
            'view' => [
                'admin.payment.log',
                'admin.payment.search',
                'admin.payment.action',
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],

    'payment_request' => [
        'label' => "Payment Request",
        'access' => [
            'view' => [
                'admin.payment.pending',
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],

    'support_ticket' => [
        'label' => "Support Ticket",
        'access' => [
            'view' => [
                'admin.ticket',
                'admin.ticket.view',
            ],
            'add' => [],
            'edit' => [
                'admin.ticket.reply',
                'admin.ticket.download',
            ],
            'delete' => [
                'admin.ticket.closed',
                'admin.ticket.delete',
            ],
        ],
    ],

    'user_management' => [
        'label' => "User Management",
        'access' => [
            'view' => [
                'admin.users',
                'admin.user.transaction',
                'admin.user.payment',
                'admin.user.payout',
                'admin.user.view.profile',

            ],
            'add' => [
                'admin.users.add'
            ],
            'edit' => [
                'admin.user.edit',
                'admin.login.as.user',
                'admin.user.email.update',
                'admin.user.username.update',
                'admin.user.update.balance',
                'admin.user.password.update',
                'admin.user.preferences.update',
                'admin.user.twoFa.update',
                'admin.user-balance-update',
                'admin.send.email',
                'admin.user.email.send',
                'admin.mail.all.user',
                'admin.email-send',
                'admin.email-send.store',
            ],
            'delete' => [
                'admin.user.delete.multiple',
                'admin.user.delete',
            ],
        ],
    ],

    'control_panel' => [
        'label' => "Control Panel",
        'access' => [
            'view' => [
                'admin.settings',
                'admin.basic.control',
                'admin.storage.index',
                'admin.maintenance.index',
                'admin.logo.settings',
                'admin.firebase.config',
                'admin.pusher.config',
                'admin.email.control',
                'admin.currency.exchange.api.config',
                'admin.email.templates',
                'admin.sms.templates',
                'admin.in.app.notification.templates',
                'admin.push.notification.templates',
                'admin.sms.controls',
                'admin.plugin.config',
                'admin.translate.api.setting',
                'admin.language.index',
                'admin.language.keywords',
                'admin.cookie',
            ],
            'add' => [
                'admin.language.create',
                'admin.language.store',
                'admin.add.language.keyword',
            ],
            'edit' => [
                'admin.basic.control.update',
                'admin.basic.control.activity.update',
                'admin.currency.exchange.api.config.update',
                'admin.storage.edit',
                'admin.storage.update',
                'admin.storage.setDefault',
                'admin.maintenance.mode.update',
                'admin.logo.update',
                'admin.firebase.config.update',
                'admin.pusher.config.update',
                'admin.email.config.edit',
                'admin.email.config.update',
                'admin.email.set.default',
                'admin.email.template.default',
                'admin.email.template.edit',
                'admin.email.template.update',
                'admin.sms.template.edit',
                'admin.sms.template.update',
                'admin.in.app.notification.template.edit',
                'admin.in.app.notification.template.update',
                'admin.push.notification.template.edit',
                'admin.push.notification.template.update',
                'admin.sms.config.edit',
                'admin.sms.config.update',
                'admin.manual.sms.method.update',
                'admin.sms.set.default',
                'admin.tawk.configuration',
                'admin.tawk.configuration.update',
                'admin.fb.messenger.configuration',
                'admin.fb.messenger.configuration.update',
                'admin.google.recaptcha.configuration',
                'admin.google.recaptcha.Configuration.update',
                'admin.google.analytics.configuration',
                'admin.google.analytics.configuration.update',
                'admin.manual.recaptcha',
                'admin.manual.recaptcha.update',
                'admin.active.recaptcha',
                'admin.translate.api.config.edit',
                'admin.translate.api.setting.update',
                'admin.translate.set.default',
                'admin.language.edit',
                'admin.language.update',
                'admin.change.language.status',
                'admin.update.language.keyword',
                'admin.single.keyword.translate',
                'admin.all.keyword.translate',
                'admin.language.update.key',
                'admin.update.cookie'
            ],
            'delete' => [
                'admin.delete.language.keyword',
                'admin.language.delete',
            ],
        ],
    ],


    'payment_gateway' => [
        'label' => "Payment Setting",
        'access' => [
            'view' => [
                'admin.payment.methods',
                'admin.deposit.manual.index'
            ],
            'add' => [
                'admin.deposit.manual.create',
                'admin.deposit.manual.store',
            ],
            'edit' => [
                'admin.edit.payment.methods',
                'admin.update.payment.methods',
                'admin.deposit.manual.edit',
                'admin.deposit.manual.update',
            ],
            'delete' => [],
        ],
    ],
    'payout_settings' => [
        'label' => "Withdraw Settings",
        'access' => [
            'view' => [
                'admin.payout.method.list',
                'admin.payout.withdraw.days',
                'admin.payout-request',
                'admin.payout-log',
                'admin.payout-log.search',
            ],
            'add' => [
                'admin.payout.method.create',
                'admin.payout.method.store',
            ],
            'edit' => [
                'admin.payout.manual.method.edit',
                'admin.payout.method.edit',
                'admin.payout.method.update',
                'admin.payout.active.deactivate',
                'admin.withdrawal.days.update',
            ],
            'delete' => [],
        ],
    ],

    'home_style' => [
        'label' => "Home Style",
        'access' => [
            'view' => [
                'admin.home.styles',
                'admin.select.home.style'
            ],
            'add' => [],
            'edit' => [],
            'delete' => [],
        ],
    ],

    'page' => [
        'label' => "Page",
        'access' => [
            'view' => [
                'admin.page.index',
            ],
            'add' => ['admin.create.page'],
            'edit' => ['admin.edit.page','admin.edit.static.page','admin.page.seo'],
            'delete' => ['admin.page.delete'],
        ],
    ],
    'manage_menu' => [
        'label' => "Manage Menu",
        'access' => [
            'view' => [
                'admin.manage.menu',
            ],
            'add' => ['admin.add.custom.link'],
            'edit' => ['admin.header.menu.item.store','admin.footer.menu.item.store','admin.edit.custom.link'],
            'delete' => ['admin.delete.custom.link'],
        ],
    ],

    'manage_content' => [
        'label' => "Manage Content",
        'access' => [
            'view' => [
                'admin.manage.content',
            ],
            'add' => ['admin.manage.content.multiple'],
            'edit' => ['admin.content.store','admin.content.item.edit'],
            'delete' => ['admin.content.item.delete'],
        ],
    ],

    'manage_blog' => [
        'label' => "Blog",
        'access' => [
            'view' => [
                'admin.blogList',
                'admin.blogCategory',
            ],
            'add' => [
                'admin.blogCategoryCreate',
                'admin.blogCategoryStore',
                'admin.blogCreate',
                'admin.blogStore',
            ],
            'edit' => [
                'admin.blogCategoryEdit',
                'admin.storage.blogCategoryUpdate',
                'admin.storage.blogEdit',
                'admin.storage.blogUpdate',
            ],
            'delete' => [
                'admin.blogCategoryDelete',
                'admin.blogDelete'
            ],
        ],
    ],
];

return $arr;



