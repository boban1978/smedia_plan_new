var Format = {
		// funkcija za formatiranje iznosa
        amount: function  (v) {
            if(v == ' ') {return v;}
            v = (Math.round((v-0)*100))/100;
            v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
            v = String(v);
            var ps = v.split('.');
            var whole = ps[0];
            var sub = ps[1] ? ','+ ps[1] : ',00';
            var r = /(\d+)(\d{3})/;
            while (r.test(whole)) {
                whole = whole.replace(r, '$1' + '.' + '$2');
            }
            v = whole + sub;
            if(v.charAt(0) == '-'){
                return '-' + v.substr(1);
            }
            return v;
        },


        // funkcija za formatiranje attachmwent-a
        attachment: function (val,store,x){
                        if (val == null) {
                            return ' '
                        } else {
                            return '<a href="'+val+'" target="_blank"><img src="Images/Icons/attach_16.png" alt="Dokument" style="border-style: none" /></a>';
                        }
       },



       spot: function (val,store,x){
                        if (val == null) {
                            return ' '
                        } else {
                            return '<a href="'+val+'" target="_blank"><img src="Images/Icons/sound_16.png" alt="Spot" style="border-style: none" /></a>';
                        }
       },
       
       seconds: function (val,store,x){
                        if (val == null) {
                            return ' '
                        } else {
                            return val + ' sec';
                        }
       }
};




