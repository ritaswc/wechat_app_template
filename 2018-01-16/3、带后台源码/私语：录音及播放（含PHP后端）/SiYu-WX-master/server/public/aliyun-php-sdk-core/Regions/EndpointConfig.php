<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
$regionIds = array("cn-hangzhou","cn-beijing","cn-qingdao","cn-hongkong","cn-shanghai","us-west-1","cn-shenzhen","ap-southeast-1");
$productDomains =array(
	new ProductDomain("Mts", "mts.cn-hangzhou.aliyuncs.com"),
	new ProductDomain("ROS", "ros.aliyuncs.com"),
	new ProductDomain("Dm", "dm.aliyuncs.com"),
	new ProductDomain("Sms", "sms.aliyuncs.com"),
	new ProductDomain("Bss", "bss.aliyuncs.com"),
	new ProductDomain("Ecs", "ecs.aliyuncs.com"),
	new ProductDomain("Oms", "oms.aliyuncs.com"),
	new ProductDomain("Rds", "rds.aliyuncs.com"),
	new ProductDomain("BatchCompute", "batchCompute.aliyuncs.com"),
	new ProductDomain("Slb", "slb.aliyuncs.com"),
	new ProductDomain("Oss", "oss-cn-hangzhou.aliyuncs.com"),
	new ProductDomain("OssAdmin", "oss-admin.aliyuncs.com"),
	new ProductDomain("Sts", "sts.aliyuncs.com"),
	new ProductDomain("Push", "cloudpush.aliyuncs.com"),
	new ProductDomain("Yundun", "yundun-cn-hangzhou.aliyuncs.com"),
	new ProductDomain("Risk", "risk-cn-hangzhou.aliyuncs.com"),
	new ProductDomain("Drds", "drds.aliyuncs.com"),
	new ProductDomain("M-kvstore", "m-kvstore.aliyuncs.com"),
	new ProductDomain("Ram", "ram.aliyuncs.com"),
	new ProductDomain("Cms", "metrics.aliyuncs.com"),
	new ProductDomain("Crm", "crm-cn-hangzhou.aliyuncs.com"),
	new ProductDomain("Ocs", "pop-ocs.aliyuncs.com"),
	new ProductDomain("Ots", "ots-pop.aliyuncs.com"),
	new ProductDomain("Dqs", "dqs.aliyuncs.com"),
	new ProductDomain("Location", "location.aliyuncs.com"),
	new ProductDomain("Ubsms", "ubsms.aliyuncs.com"),
	new ProductDomain("Drc", "drc.aliyuncs.com"),
	new ProductDomain("Ons", "ons.aliyuncs.com"),
	new ProductDomain("Aas", "aas.aliyuncs.com"),
	new ProductDomain("Ace", "ace.cn-hangzhou.aliyuncs.com"),
	new ProductDomain("Dts", "dts.aliyuncs.com"),
	new ProductDomain("R-kvstore", "r-kvstore-cn-hangzhou.aliyuncs.com"),
	new ProductDomain("PTS", "pts.aliyuncs.com"),
	new ProductDomain("Alert", "alert.aliyuncs.com"),
	new ProductDomain("Push", "cloudpush.aliyuncs.com"),
	new ProductDomain("Emr", "emr.aliyuncs.com"),
	new ProductDomain("Cdn", "cdn.aliyuncs.com"),
	new ProductDomain("COS", "cos.aliyuncs.com"),
	new ProductDomain("CF", "cf.aliyuncs.com"),
	new ProductDomain("Ess", "ess.aliyuncs.com"),
	new ProductDomain("Ubsms-inner", "ubsms-inner.aliyuncs.com"),
    new ProductDomain("Green", "green.aliyuncs.com")

	);
$endpoint = new Endpoint("cn-hangzhou", $regionIds, $productDomains);
$endpoints = array($endpoint);
EndpointProvider::setEndpoints($endpoints);