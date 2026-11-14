<?php
declare(strict_types=1);
const APP_NAME = 'IMTLA StudyHub';
const BASE_PATH = __DIR__ . '/..';
const STORAGE_PATH = BASE_PATH . '/storage';
const DATA_FILE = STORAGE_PATH . '/data.json';
const BACKUP_PATH = STORAGE_PATH . '/backups';
date_default_timezone_set('Africa/Luanda');
if (session_status() === PHP_SESSION_NONE) session_start(['cookie_httponly'=>true,'cookie_samesite'=>'Lax','use_strict_mode'=>true]);