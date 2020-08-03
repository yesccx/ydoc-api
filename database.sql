DROP TABLE IF EXISTS `y_library`;
CREATE TABLE `y_library` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `team_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '团队id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '文档库名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '文档库简介',
  `cover` varchar(512) NOT NULL DEFAULT '' COMMENT '文档库封面图片url',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序 从大到小',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文档库状态 1正常 2归档',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库表';

DROP TABLE IF EXISTS `y_library_share`;
CREATE TABLE `y_library_share` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档库id',
  `doc_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档id',
  `share_code` varchar(32) NOT NULL DEFAULT '' COMMENT '分享码',
  `share_name` varchar(32) NOT NULL DEFAULT '' COMMENT '分享名称',
  `share_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '分享简介',
  `access_password` varchar(32) NOT NULL DEFAULT '' COMMENT '访问密码',
  `access_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问计数',
  `custom_content` text NOT NULL DEFAULT '' COMMENT '自定义内容，空为全部',
  `is_protected` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否受保护 0非保护（不需要访问密码） 1受保护（需要访问密码）',
  `expire_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '过期时间 0为永不过期',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '文档库状态 0审核中 1启用 2禁用',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间（分享时间）',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库分享表';

DROP TABLE IF EXISTS `y_library_member`;
CREATE TABLE `y_library_member` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文档库id',
  `library_name` varchar(32) NOT NULL DEFAULT '' COMMENT '文档库名称(冗余字段)',
  `library_alias` varchar(32) NOT NULL DEFAULT '' COMMENT '文档库别名',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档库分组id',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序 从大到小',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员uid',
  `urole` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员角色',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员状态：0审核中 1启用 2禁用',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `apply_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请加入时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '加入团队时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库成员表';

DROP TABLE IF EXISTS `y_library_group`;
CREATE TABLE `y_library_group` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分组名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '分组简介',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序 从大到小',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库分组表';

DROP TABLE IF EXISTS `y_library_doc`;
CREATE TABLE `y_library_doc` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档库id',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档分组id',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '文档标题',
  `content` longtext NOT NULL DEFAULT '' COMMENT '文档内容',
  `editor` varchar(32) NOT NULL DEFAULT '' COMMENT '编辑器，目前可选 (markdown html text)',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序 从大到小',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库文档表';

DROP TABLE IF EXISTS `y_library_doc_history`;
CREATE TABLE `y_library_doc_history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档库id',
  `doc_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档分组id',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '文档标题',
  `content` longtext NOT NULL DEFAULT '' COMMENT '文档内容',
  `editor` varchar(32) NOT NULL DEFAULT '' COMMENT '编辑器，目前可选 (markdown html text)',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序 从大到小',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库文档历史记录表';

DROP TABLE IF EXISTS `y_library_doc_template`;
CREATE TABLE `y_library_doc_template` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人uid',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模板名称',
  `introduction` varchar(255) NOT NULL DEFAULT '' COMMENT '模板介绍',
  `content` longtext NOT NULL DEFAULT '' COMMENT '模板内容',
  `editor` varchar(32) NOT NULL DEFAULT '' COMMENT '编辑器，目前可选 (markdown html text)',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库文档模板表';

DROP TABLE IF EXISTS `y_library_doc_group`;
CREATE TABLE `y_library_doc_group` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档库id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分组id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分组名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '分组简介',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序 从大到小',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库文档分组表';

DROP TABLE IF EXISTS `y_user`;
CREATE TABLE `y_user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `account` varchar(25) NOT NULL DEFAULT '' COMMENT '用户账号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `password_salt` varchar(16) NOT NULL DEFAULT '' COMMENT '用户密码盐',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'qq号',
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '用户状态：0审核中 1正常 2禁用',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '用户表';

DROP TABLE IF EXISTS `y_user_log`;
CREATE TABLE `y_user_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `account` varchar(25) NOT NULL DEFAULT '' COMMENT '用户账号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `password_salt` varchar(16) NOT NULL DEFAULT '' COMMENT '用户密码盐',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '用户表';

DROP TABLE IF EXISTS `y_team`;
CREATE TABLE `y_team` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '团队创始人uid',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '团队名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '团队简介',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '团队logo',
  `conf` text NOT NULL DEFAULT '' COMMENT '团队配置参数',
  `member_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '团队人数',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '团队表';

DROP TABLE IF EXISTS `y_team_log`;
CREATE TABLE `y_team_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `team_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '团队id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员uid',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '日志类型 1团队日志 2成员日志',
  `content` text NOT NULL DEFAULT '' COMMENT '日志内容',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '日志记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '团队日志表';

DROP TABLE IF EXISTS `y_team_member`;
CREATE TABLE `y_team_member` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `team_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '团队id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员uid',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员状态：0审核中 1正式成员 2已禁用',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `apply_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请加入时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '加入团队时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '团队成员表';

DROP TABLE IF EXISTS `y_team_member_apply`;
CREATE TABLE `y_team_member_apply` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `team_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '团队id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员uid',
  `apply_msg`  varchar(255) NOT NULL DEFAULT '' COMMENT '申请理由',
  `answer_msg`  varchar(255) NOT NULL DEFAULT '' COMMENT '回答理由',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请状态：0审核中 1审核通过 2审核不通过',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '处理时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请加入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '团队成员申请表';

DROP TABLE IF EXISTS `y_library_log`;
CREATE TABLE `y_library_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文档库id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '成员uid',
  `operate_type` varchar(64) NOT NULL DEFAULT '' COMMENT '操作类型',
  `operate_params` text NOT NULL DEFAULT '' COMMENT '操作参数 json格式',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库相关日志表';

DROP TABLE IF EXISTS `y_user_message`;
CREATE TABLE `y_user_message` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户uid',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '已读状态 0：未读  1：已读',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `read_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '用户消息表';

DROP TABLE IF EXISTS `y_user_config`;
CREATE TABLE `y_user_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户uid',
  `config` text NOT NULL DEFAULT '' COMMENT '配置信息 json格式',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '用户配置表';

DROP TABLE IF EXISTS `y_library_config`;
CREATE TABLE `y_library_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `library_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文档库id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户uid，为0时表示文档库的设置，否则为用户对文档库的设置',
  `config` text NOT NULL DEFAULT '' COMMENT '配置信息 json格式',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间 0表示未删除',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文档库配置表';