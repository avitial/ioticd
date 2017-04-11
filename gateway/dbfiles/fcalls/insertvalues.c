#include <my_global.h>
#include <mysql.h>

void finish_with_error(MYSQL *con){
	fprintf(stderr, "%s\n", mysql_error(con));
	mysql_close(con);
	exit(1);        
}

int main(int argc, char **argv){
	MYSQL *con = mysql_init(NULL);
	
	if (con == NULL){
		fprintf(stderr, "%s\n", mysql_error(con));
		exit(1);
	}  
	// connect to database using gateway user
	if (mysql_real_connect(con, "localhost", "gateway", "team12", "sensor_database", 0, NULL, 0) == NULL){
		finish_with_error(con);
	}      
	// insert data into lora_values table
	if (mysql_query(con, "INSERT INTO lora_values (node, address, temperature, humidity, moisture) VALUES (20, '98:76:B3:C4:D5:F6', 9.8, 98.9, 89.8);")) {
	  finish_with_error(con);
	}
	mysql_close(con);
	exit(0);
}
