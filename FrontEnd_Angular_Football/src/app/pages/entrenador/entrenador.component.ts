import { Component } from '@angular/core';
import { Entrenador } from 'src/app/interfaces/entrenador.interface';
import { EntrenadorService } from 'src/app/services/entrenador.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-entrenador',
  templateUrl: './entrenador.component.html',
  styleUrls: ['./entrenador.component.css']
})
export class EntrenadorComponent {
  public entrenador!: Entrenador;

  public constructor(private entrenadorService: EntrenadorService){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.cargarEntrenador();
  }

  cargarEntrenador(){
    this.entrenadorService.obtenirDadesManager().subscribe((resp: any) => {
      console.log(resp);
      this.entrenador = resp.entrenador;
    })
  }

  actualitzarEntrenador(){
    this.entrenadorService.updateManager(this.entrenador).subscribe( resp => {
      Swal.fire('Actualitzat', 'Actualitzat correctement', 'success')
    });
  }
}
