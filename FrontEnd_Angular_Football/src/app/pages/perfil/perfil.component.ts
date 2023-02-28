import { Component } from '@angular/core';
import { EntrenadorService } from '../../services/entrenador.service';
import { Entrenador } from '../../interfaces/entrenador.interface';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.component.html',
  styleUrls: ['./perfil.component.css']
})
export class PerfilComponent {

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
