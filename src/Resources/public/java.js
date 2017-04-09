function popup(PfadWohin, PosLinks, PosOben, PosBreite, PosHoehe)
  {     if (PosLinks < 1) {PosLinks=(screen.width/2)-(PosBreite/2)};
        if (PosOben < 1){PosOben=(screen.height/2)-(PosHoehe/2)};
        Dummy = 'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,resizable=yes,scrollbars=yes,left='+PosLinks+',top='+PosOben+',width='+PosBreite+',height='+PosHoehe;
        window.open(PfadWohin, 'Rechnungsausgabe -> nlsh_Kleingartenverwaltung', Dummy);

  }