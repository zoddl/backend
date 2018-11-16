<?php
// Bucket Name
$bucket="microcompass";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIVWTLM6WN4QDNYQQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
			
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>