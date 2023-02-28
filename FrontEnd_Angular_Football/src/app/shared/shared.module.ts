import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';

import { HeaderComponent } from './header/header.component';



@NgModule({
    declarations: [
      HeaderComponent
    ],
    imports: [
      FormsModule,
      ReactiveFormsModule,
      CommonModule,
      RouterModule
    ],
    exports: [
      HeaderComponent
    ]
})
export class SharedModule { }
