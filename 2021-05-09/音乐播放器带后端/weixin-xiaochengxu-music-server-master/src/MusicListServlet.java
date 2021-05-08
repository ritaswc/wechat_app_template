

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * Servlet implementation class MusicListServlet
 */
@WebServlet("/music-list")
public class MusicListServlet extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    /**
     * @see HttpServlet#HttpServlet()
     */
    public MusicListServlet() {
        super();
        // TODO Auto-generated constructor stub
    }

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("text/json");
		PrintWriter writer = response.getWriter();
		String musicListJson = "{\"musicList\": [{\"song\": \"911\",\"artist\": \"I Do\", \"url\": \"http://localhost:8080/xiaochengxu-music/music/911%20-%20I%20Do.mp3\"}, "
				+ "{\"song\": \"Wait (Kygo Remix)\",\"artist\": \"Kygo M83\", \"url\": \"http://localhost:8080/xiaochengxu-music/music/Kygo%20M83%20-%20Wait%20(Kygo%20Remix).mp3\"},"
				+ "{\"song\": \"Reality\",\"artist\": \"Lost Frequencies/Janieck Devy\", \"url\": \"http://localhost:8080/xiaochengxu-music/music/Lost%20Frequencies%20Janieck%20Devy%20-%20Reality.mp3\"}]}";
		
		writer.println(musicListJson);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(request, response);
	}

}
