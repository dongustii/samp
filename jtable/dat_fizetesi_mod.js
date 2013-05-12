var data_table = 'adminx_dat_fizetesi_mod';

$(document).ready(function () {
        $('#PersonTableContainer').jtable({
            title: 'Fizetési mód mezõ karbantartása',
            actions: {
                listAction: 'jtable/dat_ertekek_s.php?action=list&data_table='+data_table,
				createAction: 'jtable/dat_ertekek_s.php?action=create&data_table='+data_table,
				updateAction: 'jtable/dat_ertekek_s.php?action=update&data_table='+data_table
				//deleteAction: 'jtable/dat_nyelv_s.php?action=delete&data_table='+data_table
            },
            fields: {
                sorszam: {
                    key: true,
                    list: false
                },
                ertek: {
                    title: 'Érték',
                    width: '80%'
                },
                status: {
                    title: 'Status',
                    width: '20%'
                }
            }
        });
        $('#PersonTableContainer').jtable('load');
    });
    
    