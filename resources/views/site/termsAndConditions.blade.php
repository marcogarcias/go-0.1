@extends('layouts.site')
@section('returnBtn', route('home'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-12 col-md-7">
      <div class="termsAndConditions p-5 text-white">
        <h3>Terminos y Condiciones</h3>
        <p>"Al acceder y utilizar este servicio, usted acepta y accede a estar obligado por los términos
          y disposiciones de este acuerdo. Asimismo, al utilizar estos servicios particulares, usted
          estará sujeto a toda regla o guía de uso correspondiente que se haya publicado para
          dichos servicios. Toda participación en este servicio constituirá la aceptación de este
          acuerdo. Si no acepta cumplir con lo anterior, por favor, no lo utilice".</p>
        
        <h3>Declaracion de privacidad</h3>
        <p>Información personal que recopilamos</p>
        <p>Recopilamos cierta información directamente de usted cuando nos la proporciona 
        voluntariamente, por ejemplo:</p>
        <p>Información de contacto: como su nombre, dirección, número de teléfono, dirección de
        correo electrónico y / o nombre de usuario y contraseña, si decide crearlos.</p>
        <p>Información del solicitante: información que proporciona en relación con oportunidades
        profesionales, como su currículum, cartas de presentación y datos demográcos.</p>
        <p>Otra información que proporcione: cualquier otra información que pueda proporcionarnos 
        directamente a través de nuestros sitios web para cosas como documentos técnicos o
        encuestas, o a través de las redes sociales.</p>
        <p>somosgo, recopila otra información en línea automáticamente cuando visita nuestros
        sitios web o aplicaciones, que también pueden describirse en nuestro Aviso de cookies.
        Esto puede incluir cosas como:</p> 
        <p>Información del dispositivo: incluida su dirección IP, tipos de navegador, sistema operativo, 
        tipos de dispositivo e ID de dispositivo o identicadores de publicidad.</p>
        <p>Información de uso: como su actividad de navegación mientras está en nuestros sitios
        web o aplicaciones, qué páginas visita y en qué hace clic, formularios que completa o
        comienza a completar, términos de búsqueda que usa, si abre correos electrónicos e
        interactúa con el contenido, tiempos de acceso, error registros e información similar.</p>
        <p>Somosgo también puede recibir información sobre usted de otras personas, como información 
        actualizada de la dirección si descubrimos que la información que tenemos ya no está actualizada.</p>
          
        <h3>Cómo utilizamos la información personal que recopilamos</h3>
        <p>Según sea razonablemente necesario y proporcionado para cumplir con los nes para los
        que se proporcionó la información</p>
        <p>Para proporcionar información, productos o servicios.</p>
        <p>Para auditar y medir la interacción del usuario con nuestros sitios web, para
        que podamos mejorar la relevancia o ecacia de nuestro contenido y mensajería</p>
        <p>Para desarrollar y llevar a cabo marketing, publicidad y análisis</p>
        <p>Para proporcionar mensajes de texto o correos electrónicos que contengan información
        sobre nuestros productos o servicios, o eventos o noticias, que puedan ser de interés para
        los destinatarios, según lo permita la ley</p>
        <p>Para ofrecer contenido y productos o servicios relevantes para sus intereses, incluidos
        anuncios dirigidos en sitios de terceros</p>
        <p>Para detectar incidentes de seguridad o monitorear actividades fraudulentas o ilegales</p>
        <p>Depuración para identicar y reparar errores</p>
        <p>Cumplir con leyes, regulaciones u otros procesos legales</p>

        <h3>Cómo compartimos su información personal</h3>
        <p>Podemos compartir la información que recopilamos como se describe a continuación.
        Cuando compartimos información, ya sea dentro de nuestra empresa o con otros, tomamos 
        medidas para exigir a los destinatarios que mantengan protecciones similares para la
        información que la proporcionada por somosgo.</p> 
        <p>Dentro "somosgo". Podemos compartir su información personal con otras entidades
        comerciales propiedad de "somosgo".</p>
        <p>Proveedores de servicios / Vendedores / Terceros. Trabajamos con otros para administrar o
        respaldar algunas de nuestras operaciones y servicios comerciales. Estas entidades
        pueden estar ubicadas fuera de su país de residencia o del país en el que se encuentra.</p>
        <p>Para nes legales. Podemos divulgar información relevante según sea necesario o requerido 
        para nes legales, de cumplimiento o reglamentarios. Esto incluye, por ejemplo, divulgaciones 
        que se requieren para: (i) responder a solicitudes de información debidamente autorizadas de 
        la policía y las autoridades gubernamentales; (ii) cumplir con cualquier ley,
        reglamento, citación u orden judicial; (iii) investigar y ayudar a prevenir amenazas de
        seguridad, fraude u otra actividad maliciosa; (iv) hacer cumplir / proteger los derechos y
        propiedades desomosgo o sus subsidiarias; (v) proteger los derechos o la seguridad personal 
        de somosgo, nuestros empleados y terceros; o proteger la seguridad de nuestros sitios web, 
        sistemas, información, instalaciones o propiedad.</p>
        <p>Para transacciones corporativas. De vez en cuando vendemos, compramos, fusionamos o
        reorganizamos nuestros negocios. Esas situaciones de reestructuración corporativa
        pueden implicar la divulgación de información personal a compradores potenciales o
        reales, o la recepción de la misma por parte de los vendedores.</p>
        <p>De lo contrario, con su consentimiento. También podemos compartir información sobre
        usted con terceros si le pedimos y usted da su consentimiento para tal intercambio.</p>

        <h3>Mantener su información segura</h3>
        <p>somosgo ha adoptado medidas físicas, técnicas y administrativas diseñadas para evitar el
        acceso o la divulgación no autorizados, mantener la precisión de los datos y garantizar el
        uso adecuado de la información personal. Sin embargo, no podemos garantizar ni garantizar la 
        seguridad de la información. Ninguna medida de seguridad es infalible.</p>

        <h3>¿Cómo puede ayudar a proteger su información?</h3>
        <p>Si está utilizando un sitio web o una aplicación de "somosgo" para los que se registró 
        y eligió una contraseña, no debe divulgar su contraseña a nadie. Nunca le pediremos su 
        contraseña en una llamada telefónica no solicitada o en un correo electrónico no solicitado. 
        También recuerde cerrar sesión en el sitio web de “somosgo” y cerrar la ventana de su 
        navegador cuando haya terminado su trabajo.</p>
        <p>Tenga en cuenta que el correo electrónico no cifrado no es un método seguro de transmisión, 
        ya que otros pueden acceder y ver la información contenida en dichos correos electrónicos 
        mientras están en tránsito hacia nosotros. Por esta razón, preferimos que no nos comunique 
        información condencial o sensible a través de un correo electrónico no cifrado. Sin embargo, 
        respetaremos las solicitudes de comunicaciones de los pacientes a través de correo electrónico 
        no cifrado.</p>

        <h3>Enlaces a otros sitios</h3>
        <p>Nuestros sitios web pueden contener enlaces a otros sitios web de los que no somos 
        propietarios ni operamos. Si accede a esos enlaces, abandonará nuestros sitios web. "somosgo" 
        no controla esos sitios web de terceros ni sus prácticas de privacidad, que pueden diferir 
        de las nuestras. No respaldamos ni hacemos ninguna declaración sobre sitios de terceros, 
        incluido el contenido o la seguridad de esos sitios. La información que usted elige 
        proporcionar o que es recopilada por estos terceros no está cubierta por este Aviso de 
        privacidad. Por lo tanto, le recomendamos que revise el aviso de privacidad de los sitios web 
        de terceros.</p>

        <h3>Aviso de cookies</h3>
        <p>Nuestros sitios web, como casi todos los demás sitios web, utilizan cookies y otras 
        tecnologías para recopilar y compartir información.</p>

        <h3>Privacidad de los niños</h3>
        <p>No recopilamos información de niños a sabiendas (según lo dene la ley local) y no dirigimos 
        nuestros sitios web o aplicaciones a niños. Si nos enteramos de que hemos recopilado información 
        de niños, la eliminaremos.</p>

        <h3>Aviso de no discriminación</h3>
        <p>"Somosgo" y sus subsidiarias, cumplen con las leyes federales de derechos civiles aplicables 
        y no discriminan por motivos de raza, color, nacionalidad, edad, discapacidad o sexo. "somosgo" 
        no excluye a las personas ni las trata de manera diferente debido a su raza, color, nacionalidad, 
        edad, discapacidad o sexo.</p>

        <h3>Actualizaciones de este aviso de privacidad</h3>
        <p>De vez en cuando, podemos cambiar este Aviso de privacidad. Le recomendamos que revise este 
        Aviso periódicamente para asegurarse de estar al tanto de esos cambios.</p>
        <p><strong>"Esta página y sus componentes se ofrecen únicamente con nes informativos; esta página 
        no se hace responsable de la exactitud, utilidad o disponibilidad de cualquier información que se 
        transmita o ponga a disposición a través de la misma; no será responsable por cualquier error u 
        omisión en dicha información”</strong></p>

        <h3>Derechos de propiedad intelectual.</h3>
        <p>La página y su contenido original, las características y la funcionalidad son propiedad de 
        (somosgo) y están protegidos por derechos de autor internacionales, marcas registradas, patentes, 
        secretos comerciales y demás leyes de propiedad intelectual o de derechos de propiedad</p>

        <h3>Declaración sobre la entrega de productos</h3>
        <p>En caso de solicitar la entrega de algun producto de nuestros anunciantes por medio de la 
        plataforma “somosgo” o sus aplicaciones, la resposabilidad del envio, integridad y calidad del 
        producto sera exclusivamente de la empresa o negocio que anuncia el producto. "somosgo" no se 
        hace responsable en ningun caso del reembolso, cambio o garantia de ninguno de los productos 
        o servicios anunciados. La responsabilidad es plenamente del anunciante y las aclaraciones se 
        haran directamente con el provedor del servicio o en su caso aquel que vende el producto.</p>

        <h3>Cláusula de rescisión si es necesario.</h3>
        <p>Podemos cancelar su acceso a la página, sin causa o aviso, lo cual podrá resultar en la 
        incautación y destrucción de toda la información que esté asociada con su cuenta. Todas las 
        disposiciones de este acuerdo que, por su naturaleza, deban sobrevivir a la cancelación sobrevivirán 
        a ella, incluyendo sin limitación, las disposiciones de propiedad, renuncias de garantía, indemnización 
        y limitaciones de responsabilidad.</p>

        <h3>Disposición de noticación.</h3>
        <p>La empresa se reserva el derecho de modicar estas condiciones de vez en cuando según lo considere 
        oportuno; asimismo, el uso permanente de la página signicará su aceptación de cualquier ajuste a 
        tales términos. Si hay algún cambio en nuestra política de privacidad, anunciaremos en nuestra página 
        principal y en otras páginas importantes de nuestro sitio que se han hecho tales cambios. Si hay algún 
        cambio en nuestra página respecto a la manera en que usamos la información de identicación personal de 
        nuestros clientes, enviaremos una noticación.</p>

        <h3>Uso de chat plataforma web somosgo</h3>
        <p>La creacion del chat o mensajeria web en el espacio web de "somosgo", es para facilitar la 
        comunicación entre usuarios y anunciantes, su uso es responsabilidad de cada individuo, los mensajes, 
        fotograas o en su defecto videos utilizados en el chat, son solo responsabilidad de cada usuario y 
        anunciante, "somosgo" no se responsabiliza por los temas o contenidos compartidos por cada individuo. 
        Contamos un filtros para evitar el uso indebido o ilícito de contenido, pero aceptamos que ningun metodo 
        es totalmente infalible y no nos hacemos responsables de los contenidos compartidos por los usuarios o 
        anunciantes.</p>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    $(document).ready(function() {
      
    });
  });
</script>
@endpush

@endsection