-- ==========================================================================
-- create_tables.clp
--
-- Creates the Database Schema used by the Vehicle Service Tracker
--
-- Copyright (c) 2006 Kenneth J. Snyder
-- Licensed under the GNU GPL. For full terms see the file LICENSE
-- -------------------------------------------------------------------------
--
-- Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
--
-- Revised: 23Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org v2.04
--          - Added lastlogin column in clients table
--          - Added AVG_MILES_PER_YEAR & EXAVG columns on vehicles
--
-- ==========================================================================



connect to wwwdb;

drop table vst.clients;
drop table vst.servicehist;
drop table vst.vehicles;
drop table vst.serviceline;

drop schema vst restrict;
create schema vst;

create table vst.clients (
 USRNAME                                            CHAR(30) NOT NULL,
 LAST                                               CHAR(20) NOT NULL,
 FIRST                                              CHAR(20) NOT NULL,
 EMAIL                                              VARCHAR(50) NOT NULL,
 PASSWD                                             VARCHAR(40) NOT NULL,
 C_CREATE                                           TIMESTAMP NOT NULL,
 LASTLOGIN                                          TIMESTAMP,
 SID                                                CHAR(32),
 PRIMARY KEY (USRNAME))
IN WWW;
grant all on vst.clients to public;

create table vst.vehicles (
 VIN                                                CHAR(18) NOT NULL,
 YEAR                                               INTEGER NOT NULL,
 MAKE                                               CHAR(20) NOT NULL,
 MODEL                                              CHAR(40) NOT NULL,
 COLOR                                              VARCHAR(25),
 C_CREATE                                           TIMESTAMP NOT NULL,
 OWNER                                              CHAR(30) NOT NULL,
 ODOMORK                                            CHAR(1) NOT NULL,
 MILESDRIVEN                                        INTEGER,
 COSTPERMILE                                        DECIMAL(10,2),
 TOTALSPENT                                         DECIMAL(10,2),
 TOTALHOURS                                         DECIMAL(10,1),
 IMAGE                                              VARCHAR(254),
 GASMILE                                            DECIMAL(3,1),
 GASORDIESEL                                        CHAR(1),
 AVG_MILES_PER_YEAR                                 INTEGER,
 EXAVG                                              CHAR(1),
 PRIMARY KEY (VIN))
IN WWW;
grant all on vst.vehicles to public;

create table vst.servicehist (
 REPAIR_ORDER                                       CHAR(20) NOT NULL,
 VEHICLE                                            CHAR(18) NOT NULL,
 SERVICE_DATE                                       TIMESTAMP NOT NULL,
 MILEAGE                                            INTEGER NOT NULL,
 SERVICE_SHORT                                      VARCHAR(75),
 PRIMARY KEY(REPAIR_ORDER))
IN WWW;
grant all on vst.servicehist to public;

create table vst.serviceline (
 LINE_INDEX                                         INTEGER NOT NULL
          GENERATED ALWAYS AS IDENTITY
            (START WITH 1, INCREMENT BY 1, NO CACHE),
 REPAIR_ORDER_INDEX                                 INTEGER NOT NULL,
 REPAIR_ORDER                                       CHAR(20) NOT NULL,
 LINE_DATE                                          TIMESTAMP NOT NULL,
 OPERATION                                          VARCHAR(80) NOT NULL,
 SOURCE                                             VARCHAR(50),
 PART_NUMBER                                        VARCHAR(30),
 COST                                               DECIMAL(10,2),
 HOURS                                              DECIMAL(10,2),
 NOTES                                              VARCHAR(2500),
 PRIMARY KEY(LINE_INDEX))
IN WWW;
grant all on vst.serviceline to public;

create table vst.serviceschedule (
 SCHEDULE_INDEX                                    INTEGER NOT NULL
          GENERATED ALWAYS AS IDENTITY
            (START WITH 1, INCREMENT BY 1, NO CACHE),
 OPERATION                                          VARCHAR(75) NOT NULL,
 OWNER                                              CHAR(30) NOT NULL,
 VEHICLE                                            CHAR(18) NOT NULL,
 MILEAGE_INTERVAL                                   INTEGER,
 NEXT_MILEAGE                                       INTEGER,
 SERVICE_DATE                                       DATE,
 MORD                                               CHAR(1) NOT NULL,
 C_CREATE                                           TIMESTAMP,
 PRIMARY KEY(SCHEDULE_INDEX))
 IN WWW;
grant all on vst.serviceschedule to public;
        

commit;
terminate;
