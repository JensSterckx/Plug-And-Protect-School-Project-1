-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 18 mei 2015 om 13:50
-- Serverversie: 5.5.43
-- PHP-Versie: 5.4.39-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `plugandprotect`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_logs`
--

CREATE TABLE IF NOT EXISTS `tbl_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_mconfig`
--

CREATE TABLE IF NOT EXISTS `tbl_mconfig` (
  `MAC` varchar(17) NOT NULL,
  `type` varchar(45) NOT NULL,
  `file` varchar(512) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`MAC`,`file`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `tbl_mconfig`
--

INSERT INTO `tbl_mconfig` (`MAC`, `type`, `file`, `content`) VALUES
('ff:ff:ff:ff:ff:ff', 'FSTAB', '/etc/fstab', 'proc            /proc           proc    defaults          0       0\r\n/dev/mmcblk0p1  /boot           vfat    defaults          0       2\r\n/dev/mmcblk0p2  /               ext4    defaults,noatime  0       1\r\n# a swapfile is not a swap partition, so no using swapon|off from here on, use  dphys-swapfile swap[on|off]  for that\r\n\r\n10.0.0.1:/var/www/images/motion /mnt/nfs1 nfs rsize=8192,wsize=8192,timeo=14,intr\r\n10.0.0.2:/var/www/images/motion /mnt/nfs2 nfs rsize=8192,wsize=8192,timeo=14,intr\r\n'),
('ff:ff:ff:ff:ff:ff', 'CAMERA', '/etc/motion/motion.conf', 'daemon off\r\nsetup_mode off\r\nvideodevice /dev/video0\r\nv4l2_palette 8\r\ninput 8\r\nnorm 0\r\nfrequency 0\r\nrotate 0\r\nwidth 1280\r\nheight 720\r\nframerate 100\r\nminimum_frame_time 0\r\nnetcam_tolerant_check off\r\nauto_brightness off\r\nbrightness 0\r\ncontrast 0\r\nsaturation 0\r\nhue 0\r\nroundrobin_frames 1\r\nroundrobin_skip 1\r\nswitchfilter off\r\nthreshold 1500\r\nthreshold_tune off\r\nnoise_level 32\r\nnoise_tune on\r\ndespeckle EedDl\r\nsmart_mask_speed 0\r\nlightswitch 0\r\nminimum_motion_frames 1\r\npre_capture 0\r\npost_capture 0\r\ngap 60\r\nmax_mpeg_time 0\r\noutput_all off\r\noutput_normal on\r\noutput_motion off\r\nquality 100\r\nppm off\r\nffmpeg_cap_new off\r\nffmpeg_cap_motion off\r\nffmpeg_timelapse 0\r\nffmpeg_timelapse_mode daily\r\nffmpeg_bps 400000\r\nffmpeg_variable_bitrate 0\r\nffmpeg_video_codec msmpeg4\r\nffmpeg_deinterlace off\r\nsnapshot_interval 0\r\nlocate on\r\ntext_right %Y-%m-%d\\n%T-%q\r\ntext_left {MODULE_HOSTNAME}\r\ntext_changes off\r\ntext_event %Y%m%d%H%M%S\r\ntext_double on\r\ntarget_dir /mnt/nfs1/{MAC}\r\nsnapshot_filename %v-%Y%m%d%H%M%S-snapshot\r\njpeg_filename %Y%m%d%H%M%S-%v-%q\r\nmovie_filename %Y%m%d%H%M%S-%v\r\ntimelapse_filename %Y%m%d-timelapse\r\non_picture_save /root/files/copytonfs2.sh %f\r\nwebcam_port 1027\r\nwebcam_quality 75\r\nwebcam_motion off\r\nwebcam_maxrate 30\r\nwebcam_localhost off\r\nwebcam_limit 0\r\ncontrol_port 8080\r\ncontrol_localhost off\r\ncontrol_html_output on\r\ntrack_type 0\r\ntrack_auto off\r\ntrack_motorx 0\r\ntrack_motory 0\r\ntrack_maxx 0\r\ntrack_maxy 0\r\ntrack_iomojo_id 0\r\ntrack_step_angle_x 10\r\ntrack_step_angle_y 10\r\ntrack_move_wait 10\r\ntrack_speed 255\r\ntrack_stepsize 40\r\nquiet on\r\nsql_log_image on\r\nsql_log_snapshot on\r\nsql_log_mpeg off\r\nsql_log_timelapse off\r\nsql_query insert into security(camera, filename, frame, file_type, time_stamp, event_time_stamp) values(''%t'', ''%f'', ''%q'', ''%n'', ''%Y-%m-%d %T'', ''%C'')'),
('ff:ff:ff:ff:ff:ff', 'RC.LOCAL', '/etc/rc.local', '#!/bin/sh -e\r\n#\r\n# rc.local\r\n#\r\n# This script is send by the MySQL DB for user ff:ff:ff:ff:ff:ff (all)\r\n\r\n_IP=$(hostname -I) || true\r\nif [ "$_IP" ]; then\r\n  printf "My IP address is %s\\n" "$_IP"\r\nfi\r\n\r\nprintf "\\n*********************************\\n---------------------------------\\nExecuting the configuration receiver.\\n\\n"\r\nphp -q /root/startup/1-start.php >> /root/startup/log.txt\r\nphp -q /root/startup/2-configure.php >> /root/startup/log.txt\r\n\r\necho "Initializing BCM" >> /root/startup/log.txt\r\n/root/files/jens/init > /root/startup/bcm.txt 2>&1 &\r\n\r\nmac=$(cat /sys/class/net/eth0/address)\r\nmkdir -p /mnt/nfs1/$mac\r\nmkdir -p /mnt/nfs2/$mac\r\n\r\nif [ -c /dev/video0 ]; then\r\n	echo "Starting motion" >> /root/startup/log.txt\r\n	motion > /root/startup/motion.txt 2>&1 &\r\nelse\r\n	echo "Skipping motion, no camera connected" >> /root/startup/log.txt\r\nfi\r\n\r\nexit 0\r\n'),
('ff:ff:ff:ff:ff:ff', 'WLAN', '/etc/wpa_supplicant/wpa_supplicant.conf', 'ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev\r\nupdate_config=1\r\n\r\nnetwork={\r\n        ssid="PROJECT"\r\n        psk="User-123"\r\n\r\n        proto=RSN\r\n        key_mgmt=WPA-PSK\r\n}'),
('ff:ff:ff:ff:ff:ff', 'MOTION_COPY', '/root/files/copytonfs2.sh', '#!/bin/sh\r\n\r\n#COPY FROM NFS1 TO NFS2\r\nbas=$(basename $1)\r\nmac=$(cat /sys/class/net/eth0/address)\r\ncp $1 /mnt/nfs2/$mac/$bas\r\n');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_minfo`
--

CREATE TABLE IF NOT EXISTS `tbl_minfo` (
  `MAC` varchar(17) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `DESC` varchar(256) NOT NULL,
  `TYPE` varchar(512) NOT NULL,
  PRIMARY KEY (`MAC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `tbl_minfo`
--

INSERT INTO `tbl_minfo` (`MAC`, `NAME`, `DESC`, `TYPE`) VALUES
('b8:27:eb:0f:05:c4', 'Debug module Wesley', 'Challa', 'Alarm'),
('b8:27:eb:5c:5a:de', 'wes', 'This is a new module, rename it at the settings page.', 'Camera'),
('b8:27:eb:9d:e6:3a', 'PI-JELLE', 'This is a new module, rename it at the settings page.', 'Camera+Alarm'),
('b8:27:eb:d5:dc:f0', 'Temperatuur demo', 'Temperatuur module', 'Camera+T-L-Sensor');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_modules`
--

CREATE TABLE IF NOT EXISTS `tbl_modules` (
  `MAC` varchar(17) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `HOSTNAME` text NOT NULL,
  `status` int(1) NOT NULL,
  `uptime` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`MAC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `tbl_modules`
--

INSERT INTO `tbl_modules` (`MAC`, `IP`, `HOSTNAME`, `status`, `uptime`, `last_update`) VALUES
('b8:27:eb:0f:05:c4', '10.0.128.122', 'module-wesley1', 0, 190, '2015-05-18 09:38:01'),
('b8:27:eb:5c:5a:de', '10.0.4.4', 'module-wesleypi2', 1, 4651, '2015-05-18 11:50:01'),
('b8:27:eb:9d:e6:3a', '10.0.3.253', 'pi-jelle', 0, 0, '2015-05-17 20:06:01'),
('b8:27:eb:d5:dc:f0', '10.0.128.121', 'module-jens', 0, 183, '2015-05-18 09:38:01');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_sensordata`
--

CREATE TABLE IF NOT EXISTS `tbl_sensordata` (
  `sdata_id` int(11) NOT NULL AUTO_INCREMENT,
  `sdata_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MAC` varchar(17) NOT NULL,
  `sensor_type` varchar(30) NOT NULL,
  `value` int(10) NOT NULL,
  PRIMARY KEY (`sdata_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241 ;

--
-- Gegevens worden uitgevoerd voor tabel `tbl_sensordata`
--

INSERT INTO `tbl_sensordata` (`sdata_id`, `sdata_time`, `MAC`, `sensor_type`, `value`) VALUES
(1, '2015-05-17 12:47:50', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(2, '2015-05-17 12:47:50', 'b8:27:eb:d5:dc:f0', 'light', 190),
(3, '2015-05-17 12:50:01', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(4, '2015-05-17 12:50:02', 'b8:27:eb:d5:dc:f0', 'light', 227),
(5, '2015-05-17 12:55:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(6, '2015-05-17 12:55:02', 'b8:27:eb:d5:dc:f0', 'light', 408),
(7, '2015-05-17 13:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(8, '2015-05-17 13:00:02', 'b8:27:eb:d5:dc:f0', 'light', 418),
(9, '2015-05-17 13:05:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(10, '2015-05-17 13:05:02', 'b8:27:eb:d5:dc:f0', 'light', 378),
(11, '2015-05-17 13:10:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(12, '2015-05-17 13:10:02', 'b8:27:eb:d5:dc:f0', 'light', 417),
(13, '2015-05-17 13:15:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(14, '2015-05-17 13:15:02', 'b8:27:eb:d5:dc:f0', 'light', 498),
(15, '2015-05-17 13:20:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(16, '2015-05-17 13:20:01', 'b8:27:eb:d5:dc:f0', 'light', 262),
(17, '2015-05-17 13:25:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(18, '2015-05-17 13:25:02', 'b8:27:eb:d5:dc:f0', 'light', 216),
(19, '2015-05-17 13:30:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(20, '2015-05-17 13:30:01', 'b8:27:eb:d5:dc:f0', 'light', 611),
(21, '2015-05-17 13:35:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(22, '2015-05-17 13:35:01', 'b8:27:eb:d5:dc:f0', 'light', 440),
(23, '2015-05-17 13:40:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(24, '2015-05-17 13:40:02', 'b8:27:eb:d5:dc:f0', 'light', 559),
(25, '2015-05-17 13:45:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(26, '2015-05-17 13:45:02', 'b8:27:eb:d5:dc:f0', 'light', 318),
(27, '2015-05-17 13:50:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(28, '2015-05-17 13:50:01', 'b8:27:eb:d5:dc:f0', 'light', 522),
(29, '2015-05-17 13:55:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(30, '2015-05-17 13:55:02', 'b8:27:eb:d5:dc:f0', 'light', 283),
(31, '2015-05-17 14:00:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(32, '2015-05-17 14:00:01', 'b8:27:eb:d5:dc:f0', 'light', 194),
(33, '2015-05-17 14:05:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(34, '2015-05-17 14:05:02', 'b8:27:eb:d5:dc:f0', 'light', 286),
(35, '2015-05-17 14:10:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(36, '2015-05-17 14:10:02', 'b8:27:eb:d5:dc:f0', 'light', 549),
(37, '2015-05-17 14:15:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(38, '2015-05-17 14:15:02', 'b8:27:eb:d5:dc:f0', 'light', 272),
(39, '2015-05-17 14:20:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(40, '2015-05-17 14:20:02', 'b8:27:eb:d5:dc:f0', 'light', 565),
(41, '2015-05-17 14:25:01', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(42, '2015-05-17 14:25:01', 'b8:27:eb:d5:dc:f0', 'light', 294),
(43, '2015-05-17 14:30:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(44, '2015-05-17 14:30:02', 'b8:27:eb:d5:dc:f0', 'light', 611),
(45, '2015-05-17 14:35:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(46, '2015-05-17 14:35:02', 'b8:27:eb:d5:dc:f0', 'light', 271),
(47, '2015-05-17 14:40:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(48, '2015-05-17 14:40:02', 'b8:27:eb:d5:dc:f0', 'light', 428),
(49, '2015-05-17 14:45:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(50, '2015-05-17 14:45:02', 'b8:27:eb:d5:dc:f0', 'light', 606),
(51, '2015-05-17 14:50:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(52, '2015-05-17 14:50:02', 'b8:27:eb:d5:dc:f0', 'light', 469),
(53, '2015-05-17 14:55:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(54, '2015-05-17 14:55:02', 'b8:27:eb:d5:dc:f0', 'light', 497),
(55, '2015-05-17 15:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(56, '2015-05-17 15:00:02', 'b8:27:eb:d5:dc:f0', 'light', 727),
(57, '2015-05-17 15:05:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(58, '2015-05-17 15:05:02', 'b8:27:eb:d5:dc:f0', 'light', 581),
(59, '2015-05-17 15:10:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(60, '2015-05-17 15:10:02', 'b8:27:eb:d5:dc:f0', 'light', 391),
(61, '2015-05-17 15:15:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(62, '2015-05-17 15:15:02', 'b8:27:eb:d5:dc:f0', 'light', 398),
(63, '2015-05-17 15:20:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(64, '2015-05-17 15:20:02', 'b8:27:eb:d5:dc:f0', 'light', 440),
(65, '2015-05-17 15:25:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(66, '2015-05-17 15:25:02', 'b8:27:eb:d5:dc:f0', 'light', 607),
(67, '2015-05-17 15:30:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(68, '2015-05-17 15:30:02', 'b8:27:eb:d5:dc:f0', 'light', 450),
(69, '2015-05-17 15:35:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(70, '2015-05-17 15:35:02', 'b8:27:eb:d5:dc:f0', 'light', 317),
(71, '2015-05-17 15:40:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(72, '2015-05-17 15:40:02', 'b8:27:eb:d5:dc:f0', 'light', 268),
(73, '2015-05-17 15:45:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(74, '2015-05-17 15:45:01', 'b8:27:eb:d5:dc:f0', 'light', 444),
(75, '2015-05-17 15:50:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(76, '2015-05-17 15:50:02', 'b8:27:eb:d5:dc:f0', 'light', 543),
(77, '2015-05-17 15:55:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(78, '2015-05-17 15:55:02', 'b8:27:eb:d5:dc:f0', 'light', 358),
(79, '2015-05-17 16:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(80, '2015-05-17 16:00:02', 'b8:27:eb:d5:dc:f0', 'light', 306),
(81, '2015-05-17 16:05:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(82, '2015-05-17 16:05:01', 'b8:27:eb:d5:dc:f0', 'light', 564),
(83, '2015-05-17 16:10:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(84, '2015-05-17 16:10:01', 'b8:27:eb:d5:dc:f0', 'light', 344),
(85, '2015-05-17 16:15:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(86, '2015-05-17 16:15:02', 'b8:27:eb:d5:dc:f0', 'light', 493),
(87, '2015-05-17 16:20:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(88, '2015-05-17 16:20:02', 'b8:27:eb:d5:dc:f0', 'light', 708),
(89, '2015-05-17 16:25:03', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(90, '2015-05-17 16:25:03', 'b8:27:eb:d5:dc:f0', 'light', 364),
(91, '2015-05-17 16:30:05', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(92, '2015-05-17 16:30:05', 'b8:27:eb:d5:dc:f0', 'light', 481),
(93, '2015-05-17 16:35:02', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(94, '2015-05-17 16:35:02', 'b8:27:eb:d5:dc:f0', 'light', 379),
(95, '2015-05-17 16:40:04', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(96, '2015-05-17 16:40:04', 'b8:27:eb:d5:dc:f0', 'light', 368),
(97, '2015-05-17 16:45:04', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(98, '2015-05-17 16:45:04', 'b8:27:eb:d5:dc:f0', 'light', 333),
(99, '2015-05-17 16:50:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(100, '2015-05-17 16:50:01', 'b8:27:eb:d5:dc:f0', 'light', 360),
(101, '2015-05-17 16:55:04', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(102, '2015-05-17 16:55:04', 'b8:27:eb:d5:dc:f0', 'light', 399),
(103, '2015-05-17 17:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(104, '2015-05-17 17:00:02', 'b8:27:eb:d5:dc:f0', 'light', 353),
(105, '2015-05-17 17:05:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(106, '2015-05-17 17:05:01', 'b8:27:eb:d5:dc:f0', 'light', 409),
(107, '2015-05-17 17:10:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(108, '2015-05-17 17:10:02', 'b8:27:eb:d5:dc:f0', 'light', 325),
(109, '2015-05-17 17:15:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(110, '2015-05-17 17:15:02', 'b8:27:eb:d5:dc:f0', 'light', 370),
(111, '2015-05-17 17:20:01', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(112, '2015-05-17 17:20:02', 'b8:27:eb:d5:dc:f0', 'light', 398),
(113, '2015-05-17 17:25:01', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(114, '2015-05-17 17:25:02', 'b8:27:eb:d5:dc:f0', 'light', 405),
(115, '2015-05-17 17:30:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(116, '2015-05-17 17:30:02', 'b8:27:eb:d5:dc:f0', 'light', 354),
(117, '2015-05-17 17:35:01', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(118, '2015-05-17 17:35:02', 'b8:27:eb:d5:dc:f0', 'light', 289),
(119, '2015-05-17 17:40:01', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(120, '2015-05-17 17:40:02', 'b8:27:eb:d5:dc:f0', 'light', 245),
(121, '2015-05-17 17:45:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(122, '2015-05-17 17:45:02', 'b8:27:eb:d5:dc:f0', 'light', 206),
(123, '2015-05-17 17:50:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(124, '2015-05-17 17:50:02', 'b8:27:eb:d5:dc:f0', 'light', 169),
(125, '2015-05-17 17:55:01', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(126, '2015-05-17 17:55:02', 'b8:27:eb:d5:dc:f0', 'light', 134),
(127, '2015-05-17 18:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(128, '2015-05-17 18:00:02', 'b8:27:eb:d5:dc:f0', 'light', 98),
(129, '2015-05-17 18:05:02', 'b8:27:eb:d5:dc:f0', 'temperature', 30),
(130, '2015-05-17 18:05:02', 'b8:27:eb:d5:dc:f0', 'light', 119),
(131, '2015-05-17 20:07:13', 'b8:27:eb:d5:dc:f0', 'temperature', 27),
(132, '2015-05-17 20:07:13', 'b8:27:eb:d5:dc:f0', 'light', 105),
(133, '2015-05-17 20:10:19', 'b8:27:eb:d5:dc:f0', 'temperature', 27),
(134, '2015-05-17 20:10:19', 'b8:27:eb:d5:dc:f0', 'light', 106),
(135, '2015-05-17 20:20:01', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(136, '2015-05-17 20:20:02', 'b8:27:eb:d5:dc:f0', 'light', 113),
(137, '2015-05-17 20:25:16', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(138, '2015-05-17 20:25:16', 'b8:27:eb:d5:dc:f0', 'light', 110),
(139, '2015-05-17 20:30:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(140, '2015-05-17 20:30:02', 'b8:27:eb:d5:dc:f0', 'light', 114),
(141, '2015-05-17 20:35:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(142, '2015-05-17 20:35:02', 'b8:27:eb:d5:dc:f0', 'light', 113),
(143, '2015-05-17 20:40:15', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(144, '2015-05-17 20:40:15', 'b8:27:eb:d5:dc:f0', 'light', 113),
(145, '2015-05-17 20:45:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(146, '2015-05-17 20:45:02', 'b8:27:eb:d5:dc:f0', 'light', 113),
(147, '2015-05-17 20:50:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(148, '2015-05-17 20:50:02', 'b8:27:eb:d5:dc:f0', 'light', 111),
(149, '2015-05-17 20:55:15', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(150, '2015-05-17 20:55:16', 'b8:27:eb:d5:dc:f0', 'light', 111),
(151, '2015-05-17 21:00:15', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(152, '2015-05-17 21:00:16', 'b8:27:eb:d5:dc:f0', 'light', 111),
(153, '2015-05-17 21:05:02', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(154, '2015-05-17 21:05:02', 'b8:27:eb:d5:dc:f0', 'light', 111),
(155, '2015-05-17 21:10:18', 'b8:27:eb:d5:dc:f0', 'temperature', 29),
(156, '2015-05-17 21:10:18', 'b8:27:eb:d5:dc:f0', 'light', 111),
(157, '2015-05-17 21:16:45', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(158, '2015-05-17 21:16:46', 'b8:27:eb:d5:dc:f0', 'light', 105),
(159, '2015-05-17 21:20:02', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(160, '2015-05-17 21:20:02', 'b8:27:eb:d5:dc:f0', 'light', 105),
(161, '2015-05-17 21:25:02', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(162, '2015-05-17 21:25:02', 'b8:27:eb:d5:dc:f0', 'light', 106),
(163, '2015-05-18 06:29:59', 'b8:27:eb:d5:dc:f0', 'temperature', 27),
(164, '2015-05-18 06:29:59', 'b8:27:eb:d5:dc:f0', 'light', 1026),
(165, '2015-05-18 06:31:00', 'b8:27:eb:d5:dc:f0', 'temperature', 28),
(166, '2015-05-18 06:31:00', 'b8:27:eb:d5:dc:f0', 'light', 1026),
(167, '2015-05-18 06:35:15', 'b8:27:eb:d5:dc:f0', 'temperature', 31),
(168, '2015-05-18 06:35:15', 'b8:27:eb:d5:dc:f0', 'light', 1022),
(169, '2015-05-18 06:40:01', 'b8:27:eb:d5:dc:f0', 'temperature', 32),
(170, '2015-05-18 06:40:02', 'b8:27:eb:d5:dc:f0', 'light', 946),
(171, '2015-05-18 06:45:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(172, '2015-05-18 06:45:02', 'b8:27:eb:d5:dc:f0', 'light', 57),
(173, '2015-05-18 06:50:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(174, '2015-05-18 06:50:02', 'b8:27:eb:d5:dc:f0', 'light', 31),
(175, '2015-05-18 06:55:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(176, '2015-05-18 06:55:02', 'b8:27:eb:d5:dc:f0', 'light', 30),
(177, '2015-05-18 07:00:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(178, '2015-05-18 07:00:02', 'b8:27:eb:d5:dc:f0', 'light', 24),
(179, '2015-05-18 07:05:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(180, '2015-05-18 07:05:02', 'b8:27:eb:d5:dc:f0', 'light', 39),
(181, '2015-05-18 07:10:02', 'b8:27:eb:d5:dc:f0', 'temperature', 34),
(182, '2015-05-18 07:10:02', 'b8:27:eb:d5:dc:f0', 'light', 35),
(183, '2015-05-18 07:15:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(184, '2015-05-18 07:15:02', 'b8:27:eb:d5:dc:f0', 'light', 32),
(185, '2015-05-18 07:20:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(186, '2015-05-18 07:20:02', 'b8:27:eb:d5:dc:f0', 'light', 35),
(187, '2015-05-18 07:25:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(188, '2015-05-18 07:25:02', 'b8:27:eb:d5:dc:f0', 'light', 38),
(189, '2015-05-18 07:30:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(190, '2015-05-18 07:30:02', 'b8:27:eb:d5:dc:f0', 'light', 28),
(191, '2015-05-18 07:35:02', 'b8:27:eb:d5:dc:f0', 'temperature', 34),
(192, '2015-05-18 07:35:02', 'b8:27:eb:d5:dc:f0', 'light', 39),
(193, '2015-05-18 07:40:01', 'b8:27:eb:d5:dc:f0', 'temperature', 34),
(194, '2015-05-18 07:40:01', 'b8:27:eb:d5:dc:f0', 'light', 48),
(195, '2015-05-18 07:45:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(196, '2015-05-18 07:45:02', 'b8:27:eb:d5:dc:f0', 'light', 46),
(197, '2015-05-18 07:50:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(198, '2015-05-18 07:50:01', 'b8:27:eb:d5:dc:f0', 'light', 151),
(199, '2015-05-18 07:55:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(200, '2015-05-18 07:55:01', 'b8:27:eb:d5:dc:f0', 'light', 100),
(201, '2015-05-18 08:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 34),
(202, '2015-05-18 08:00:02', 'b8:27:eb:d5:dc:f0', 'light', 141),
(203, '2015-05-18 08:05:02', 'b8:27:eb:d5:dc:f0', 'temperature', 34),
(204, '2015-05-18 08:05:02', 'b8:27:eb:d5:dc:f0', 'light', 110),
(205, '2015-05-18 08:10:02', 'b8:27:eb:d5:dc:f0', 'temperature', 34),
(206, '2015-05-18 08:10:02', 'b8:27:eb:d5:dc:f0', 'light', 218),
(207, '2015-05-18 08:15:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(208, '2015-05-18 08:15:02', 'b8:27:eb:d5:dc:f0', 'light', 103),
(209, '2015-05-18 08:20:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(210, '2015-05-18 08:20:01', 'b8:27:eb:d5:dc:f0', 'light', 119),
(211, '2015-05-18 08:25:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(212, '2015-05-18 08:25:02', 'b8:27:eb:d5:dc:f0', 'light', 149),
(213, '2015-05-18 08:30:01', 'b8:27:eb:d5:dc:f0', 'temperature', 32),
(214, '2015-05-18 08:30:01', 'b8:27:eb:d5:dc:f0', 'light', 186),
(215, '2015-05-18 08:35:01', 'b8:27:eb:d5:dc:f0', 'temperature', 32),
(216, '2015-05-18 08:35:01', 'b8:27:eb:d5:dc:f0', 'light', 101),
(217, '2015-05-18 08:40:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(218, '2015-05-18 08:40:01', 'b8:27:eb:d5:dc:f0', 'light', 102),
(219, '2015-05-18 08:45:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(220, '2015-05-18 08:45:02', 'b8:27:eb:d5:dc:f0', 'light', 86),
(221, '2015-05-18 08:50:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(222, '2015-05-18 08:50:01', 'b8:27:eb:d5:dc:f0', 'light', 111),
(223, '2015-05-18 08:55:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(224, '2015-05-18 08:55:02', 'b8:27:eb:d5:dc:f0', 'light', 135),
(225, '2015-05-18 09:00:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(226, '2015-05-18 09:00:02', 'b8:27:eb:d5:dc:f0', 'light', 98),
(227, '2015-05-18 09:05:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(228, '2015-05-18 09:05:02', 'b8:27:eb:d5:dc:f0', 'light', 195),
(229, '2015-05-18 09:10:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(230, '2015-05-18 09:10:02', 'b8:27:eb:d5:dc:f0', 'light', 139),
(231, '2015-05-18 09:15:01', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(232, '2015-05-18 09:15:01', 'b8:27:eb:d5:dc:f0', 'light', 107),
(233, '2015-05-18 09:20:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(234, '2015-05-18 09:20:02', 'b8:27:eb:d5:dc:f0', 'light', 55),
(235, '2015-05-18 09:25:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(236, '2015-05-18 09:25:02', 'b8:27:eb:d5:dc:f0', 'light', 152),
(237, '2015-05-18 09:30:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(238, '2015-05-18 09:30:02', 'b8:27:eb:d5:dc:f0', 'light', 95),
(239, '2015-05-18 09:35:02', 'b8:27:eb:d5:dc:f0', 'temperature', 33),
(240, '2015-05-18 09:35:02', 'b8:27:eb:d5:dc:f0', 'light', 108);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(45) NOT NULL,
  `prename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `passwordkey` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `uname`, `prename`, `lastname`, `password`, `passwordkey`) VALUES
(1, 'test', 'Jens', 'Sterckx', '21CAplOLEsGLE', '21cdedd99c19e2fe8eac675929ca87f70f2c2a4931649583a211d9b6ef1580cd');

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `tbl_minfo`
--
ALTER TABLE `tbl_minfo`
  ADD CONSTRAINT `tbl_minfo_ibfk_1` FOREIGN KEY (`MAC`) REFERENCES `tbl_modules` (`MAC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
