import { Component } from '@angular/core';
import { Partit } from 'src/app/interfaces/partit.interface';
import { Usuari } from 'src/app/models/usuari.model';
import { AuthService } from 'src/app/services/auth.service';
import { PartitsService } from '../../services/partits.service';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-veure-partits',
  templateUrl: './veure-partits.component.html',
  styleUrls: ['./veure-partits.component.css']
})
export class VeurePartitsComponent {

  public partits: Partit[] = [];
  public usuari!: Usuari;

  constructor(private authService: AuthService,
              private partitsService: PartitsService,
              private router: Router){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.usuari = this.authService.usuari;
    this.allGameClub(this.usuari.club_id);
  }

  allGameClub(id: number){

    this.partitsService.allGamesSquad(id).subscribe((resp: any) => {
      //console.log(resp);
      this.partits = resp.partits;
    });

  }

  generardadesPartit(partit: Partit){

    this.partitsService.crearDadesPartit(partit.id).subscribe((resp: any) => {
      //console.log(resp);
      this.allGameClub(this.usuari.id);
    }, (err) => {
      Swal.fire('Error', err.error.msg, 'error')
    });

  }

}
