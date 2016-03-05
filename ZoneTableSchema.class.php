<?php

/**
 * Class ZoneTable
 * Describes the Zone table name and column headers in the database.
 */
abstract class ZoneTableSchema
{
    const table_name = "zoneTbl";
    const id = "id";
    const user_id = "user_id";
    const name = "name";
    const lat = "lat";
    const lng = "lng";
    const radius = "radius";
    const hasSynced = "hasSynced";
    const blockingApps = "blockingApps";
    const keywords = "keywords";

    public static function make_sql_table_string() {
        return "CREATE TABLE if not exists " . ZoneTableSchema::table_name . "
            (
            " . ZoneTableSchema::id . " INT NOT NULL,
            " . ZoneTableSchema::user_id . " TEXT,
            " . ZoneTableSchema::name . " TEXT,
            " . ZoneTableSchema::lat . " REAL,
            " . ZoneTableSchema::lng . " REAL,
            " . ZoneTableSchema::radius . " REAL,
            " . ZoneTableSchema::hasSynced . " INT,
            " . ZoneTableSchema::blockingApps . " TEXT,
            " . ZoneTableSchema::keywords . " TEXT
            )";
    }
}