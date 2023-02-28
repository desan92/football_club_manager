import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { Usuari } from '../../models/usuari.model';
import { Router } from '@angular/router';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent {

  public usuari!: Usuari;

  constructor(private AuthService: AuthService,
              private router: Router){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.usuari = this.AuthService.usuari;
  }

  logOut(){
    this.AuthService.logout().subscribe(resp => {
      console.log(resp);
      this.router.navigateByUrl('/login');
    })
  }
}
