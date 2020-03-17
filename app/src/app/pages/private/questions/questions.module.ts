import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule  } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { QuestionsPageRoutingModule } from './questions-routing.module';

import { QuestionsPage } from './questions.page';

import { ComponentsModule } from '../../../components/components.module';
import { TagInputModule } from 'ngx-chips';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    QuestionsPageRoutingModule,
    ComponentsModule,
    TagInputModule
  ],
  declarations: [QuestionsPage]
})
export class QuestionsPageModule {}
