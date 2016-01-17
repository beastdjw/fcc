echo "installing fcc"
echo "remember: 2 variables has to change in fcc.py (log and db), and 1 variable in index.php (db)"
read -p "Are you sure you want to continue? <y/N> " prompt
if [[ $prompt == "y" || $prompt == "Y" || $prompt == "yes" || $prompt == "Yes" ]]
then
        cp fcc.py /var/lib/fcc
        rm /var/lib/fcc/fcc.sqlite
        sudo cp index.php /var/www/html
        #sqlite3 /var/lib/fcc/fcc.sqlite < create-fccdb.sql
        #read -p "Do you want to fill the db immediately? <y/N> " prompt
        if [[ $prompt == "y" || $prompt == "Y" || $prompt == "yes" || $prompt == "Yes" ]]
        then
                sudo python /var/lib/fcc/fcc.py
        fi
else
        exit 0
fi
