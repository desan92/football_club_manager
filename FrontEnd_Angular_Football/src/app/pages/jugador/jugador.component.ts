import { Component } from '@angular/core';
import { Entrenador } from 'src/app/interfaces/entrenador.interface';
import { JugadorService } from 'src/app/services/jugador.service';
import { Router, ActivatedRoute } from '@angular/router';
import Swal from 'sweetalert2';
import { Jugador } from 'src/app/interfaces/jugador.interface';

@Component({
  selector: 'app-jugador',
  templateUrl: './jugador.component.html',
  styleUrls: ['./jugador.component.css']
})
export class JugadorComponent {
  public jugador!: Jugador;
  public posicions = ["POR", "DEF", "MC", "DEL"];

  public constructor(private jugadorService: JugadorService,
                      private ActivatedRoute: ActivatedRoute){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.ActivatedRoute.params.subscribe( ({id}) => {
      this.cargarJugador(id);
      
      //console.log(id);
    })
  }

  cargarJugador(id: number){
    this.jugadorService.obtenirDadesJugador(id).subscribe((resp: any) => {
      console.log(resp);
      this.jugador = resp.jugador;
    })
  }

  actualitzarJugador(){
    this.jugadorService.updateJugador(this.jugador).subscribe( resp => {
      console.log(resp);
      Swal.fire('Actualitzat', 'Actualitzat correctement', 'success')
    });
  }
}
